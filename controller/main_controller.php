<?php
/**
*
* 2FA extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Paul Sohier
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace paul999\tfa\event;

use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use u2flib_server\U2F;

/**
 * Controller
 */
class main_controller
{
	/**
	 * @var helper
	 */
	private $controller_helper;

	/**
	 * @var template
	 */
	private $template;

	/**
	 * @var driver_interface
	 */
	private $db;

	/**
	 * @var user
	 */
	private $user;

	/**
	 * @var request_interface
	 */
	private $request;

	/**
	 * @var string
	 */
	private $user_table;

	/**
	 * @var string
	 */
	private $registration_table;

	private $u2f;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param helper $controller_helper
	 * @param driver_interface $db
	 * @param template $template
	 * @param string $user_table
	 * @param string $registration_table
	 */
	public function __construct(helper $controller_helper, driver_interface $db, template $template, user $user, request_interface $request, $user_table, $registration_table)
	{
		$this->controller_helper 	= $controller_helper;
		$this->template 			= $template;
		$this->db					= $db;
		$this->user					= $user;
		$this->request				= $request;
		$this->user_table			= $user_table;
		$this->registration_table	= $registration_table;

		$scheme = $this->request->is_secure() ? 'https://' : 'http://';
		$this->u2f = new U2F($scheme . $this->request->server('HTTP_HOST'));
	}

	/**
	 * @param int $user_id
	 * @param bool $admin
	 * @param bool $auto_login
	 * @param bool $viewonline
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function display($user_id, $admin, $auto_login, $viewonline)
	{
		if ($this->user->data['user_id'] != ANONYMOUS || $user_id == ANONYMOUS)
		{
			throw new AccessDeniedHttpException();
		}

		$sql = 'SELECT username FROM ' . $this->user_table . ' WHERE user_id = ' . (int)$user_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			throw new AccessDeniedHttpException();
		}
		$sql = 'SELECT * FROM ' . $this->registration_table . ' WHERE user_id = ' . (int)$user_id;
		$result = $this->db->sql_query($sql);
		$rows = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$rows[] = $this->array_to_object($row);
		}

		$this->db->sql_freeresult($result);
		$registrations = json_encode($this->u2f->getAuthenticateData($rows));

		$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET u2f_request = \'' . $this->db->sql_escape($registrations) . '\'
					WHERE
						session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
						user_id = ' . (int)$this->user->data['user_id'];
		$count = $this->db->sql_affectedrows();

		if ($count != 1)
		{
			if ($count > 1)
			{
				// Reset sessions table. We had multiple sessions with same ID!!!
				$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET u2f_request = \'\'
					WHERE
						session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
						user_id = ' . (int)$this->user->data['user_id'];
				$this->db->sql_query($sql);
			}
			throw new BadRequestHttpException('UNABLE_TO_UPDATE_SESSION');
		}

		$this->template->assign_vars(array(
			'USERNAME'		=> $row['username'],
			'U2F_REQ'		=> $registrations,
			'U_SUBMIT_AUTH'	=> $this->controller_helper->route('paul999_tfa_read_controller_submit', array(
				'user_id'		=>$user_id,
				'admin'			=> $admin,
				'auto_login'	=> $auto_login,
				'viewonline'	=> $viewonline,
			)),
		));

		return $this->controller_helper->render('@paul999_tfa/authenticate_main.html');
	}


	/**
	 * @param int $user_id
	 * @param bool $admin
	 * @param bool $auto_login
	 * @param bool $viewonline
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function submit($user_id, $admin, $auto_login, $viewonline)
	{
		$reg = $this->u2f->doAuthenticate(json_decode($_SESSION['authReq']), getRegs($user->id), json_decode($_POST['authenticate2']));

	}

	/**
	 * convert a array to a object
	 * @param array $input_array
	 * @return object
	 */
	function array_to_object($input_array) {
		return (object) array_map(array($this, 'array_to_object'), $input_array);
	}
}
