<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */

class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $name = 'Users';
	public $components = array('Session','RequestHandler','Paginator');
	var $uses = array('User','Country','SiteSetting','Newsletter' , 'Content');

/**
 * index method
 *
 * @return void
 */
 	public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow('home','index','forgotpassword','admin_index','login','admin_captcha','logout','admin_login','admin_index','admin_forgotpassword','emailExists','signup','confirm','thankyou','contact','autoLogin','autosignup','autosignuplogin','captcha','checkemail','check_paypal_email','how_log_ago','browser_timezone','fblogin','twitter_login','set_email','gpluslogin');
		}
	
	public function index() {
            
                $this->loadModel("Score");
            
		$options = array('conditions' => array('Content.page_name' => 'How_it_work_home'));
		$how = $this->Content->find('first',$options);
		
		$options = array('conditions' => array('Content.page_name' => 'Browse_home'));
		$browse = $this->Content->find('first',$options);
		
		$options = array('conditions' => array('Content.page_name' => 'Learn_home'));
		$learn = $this->Content->find('first',$options);
		
		$options = array('conditions' => array('Content.page_name' => 'Create_home'));
		$create = $this->Content->find('first',$options);
		
                 
                 $options = array('conditions' => array("Score.is_paid"=>1,"Score.is_private"=>0,"Score.end_date >="=>gmdate(date("Y-m-d H:i:s"))),'order'=>array('Score.id desc'));
		 $scores = $this->Score->find('all', $options);
                 
		 #pr($sliders);
		 //$this->set('sliders',$sliders);
		 $title_for_layout = 'Free crowdfunding';
		$this->set(compact('title_for_layout','how','browse','learn','create','startups','scores'));
	}

	public function admin_index() {
		$title_for_layout = 'Admin Login';
		$this->set(compact('title_for_layout'));
	}
	
	public function admin_list() {
		$title_for_layout = 'User List';
		$conditions=array("User.is_admin"=>0);
		$this->set(compact('title_for_layout'));
		$this->User->recursive = 0;
		$this->Paginator->settings=array("conditions"=>$conditions,'order'=>'User.id desc');
		$this->set('users', $this->Paginator->paginate());
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

	public function admin_view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}
	
	
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}
        
        
        
        
        
	
	
	public function admin_add() {
		$title_for_layout = 'User Add';
		$this->set(compact('title_for_layout'));
		if ($this->request->is('post')) {
				$options = array('conditions' => array('User.email'  => $this->request->data['User']['email']));
				$emailexists = $this->User->find('first', $options);
				if(!$emailexists)
				{
					$this->request->data['User']['password'] = $this->request->data['User']['password'];
					$this->request->data['User']['username']=$this->request->data['User']['first_name'].rand();
					$this->request->data['User']['city'] = '';
					$this->request->data['User']['country'] = 0;
					$this->request->data['User']['registration_date'] = date('Y-m-d');
					$this->User->create();
					#pr($this->data);
					#exit;
					if ($this->User->save($this->request->data)) {
						$this->Session->setFlash(__('The user has been saved.', 'default', array('class' => 'success')));
						return $this->redirect(array('action' => 'admin_list'));
					} else {
						$this->Session->setFlash(__('The user could not be saved. Please, try again.', 'default', array('class' => 'error')));
					}
					
				} else {
					$this->Session->setFlash(__('Email already exists. Please, try another.', 'default', array('class' => 'error')));
				}
			
			
		} 
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
	}
	
	
	public function admin_edit($id = null) {
		$title_for_layout = 'User Edit';
		$this->set(compact('title_for_layout'));
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if(isset($this->request->data['User']['password']) && $this->request->data['User']['password']!='')
			{
				$this->request->data['User']['password'] = $this->request->data['User']['password'];
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('The user has been saved.'));
					return $this->redirect(array('action' => 'admin_edit/'.$id));
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
				}
			}
			else
			{
				$this->request->data['User']['password'] = $this->request->data['User']['hidpw'];
				if ($this->User->save($this->request->data)) {
					$prev_pass=$this->request->data['User']['hidpw'];
					$this->User->query('Update thoughtful.users as user set user.password="'.$prev_pass.'" where user.id='.$id.'');
					$this->Session->setFlash(__('The user has been saved.'));
					return $this->redirect(array('action' => 'admin_edit/'.$id));
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
				}
			}
			
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		
		$this->set(compact('title_for_layout','countries','countryname'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function admin_delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'admin_list'));
	}


/**
*admin login and other related method
*
*/

	public function admin_login() {
		if ($this->request->is('post')) {
		#pr($this->request->data);exit;
			/*$options = array('conditions' => array('User.email'  => $this->request->data['User']['emaill'],'User.password' => md5($this->request->data['User']['passwordl']),'User.is_admin'=>1));
			$loginuser = $this->User->find('first', $options);
			if(!$loginuser){
				$this->Session->setFlash(__('Invalid email or password, try again', 'default', array('class' => 'error')));
				return $this->redirect(array('action' => 'admin_index'));
			} else {
				$this->Session->write('userid', $loginuser['User']['id']);
				$this->Session->setFlash(__('You have been successfully logged in', 'default', array('class' => 'success')));
				return $this->redirect(array('action' => 'admin_edit',$loginuser['User']['id']));
			}*/
			if ($this->Auth->login()) {
						if($this->Session->read('Auth.User.is_admin')==1)
						{
							$this->Session->setFlash('You have been successfully logged in', 'default', array('class' => 'success'));
							//return $this->redirect(array('action' => 'admin_dashboard'));
							return $this->redirect($this->Auth->redirect('/admin/users/edit/'.$this->Session->read('Auth.User.id')));
						}
						else{
							if($this->Auth->logout()){
								$this->Session->setFlash('Sorry wrong Email id or Password');
								$this->redirect(array('action' => 'index'));
							}
						}
						
					}
					else
					{
					$this->Session->setFlash(__('Invalid email or password, try again', 'default', array('class' => 'error')));
					return $this->redirect(array('action' => 'index'));
					}
		}
	}
	
	public function admin_logout() {
		if($this->Auth->logout()){
			$this->redirect(array('action' => 'index'));
		}
	}

	public function admin_forgotpassword(){
		$title_for_layout = 'Forgot Password';
		$this->set(compact('title_for_layout'));
		if ($this->request->is(array('post', 'put'))) {
			$options = array('conditions' => array('User.email' => $this->request->data['User']['emailL']));
			$user = $this->User->find('first', $options);			
			if($user){
				$length = 6;
				$chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.'0123456789';
				$str = '';
				$max = strlen($chars) - 1;
				for ($i=0; $i < $length; $i++)
				$str .= $chars[rand(0, $max)];

				$password = $str;
				$table = '<table style="width:400px;border:0px;">
							<tr>
								<td style="width:100px;">Email&nbsp;:</td>
								<td>'.$user['User']['email'].'</td>
							</tr>
							<tr>
								<td style="width:100px;">Password&nbsp;:</td>
								<td>'.$password.'</td>
							</tr>
							</table>';
				$msg_body = 'Hi '.$user['User']['first_name'].'<br/><br/>Your new password has been successfully regenarated. The new login details are as follows:<br/>'.$table.' <br/><br/>Thanks,<br/>Thoughtful';

				$this->request->data['User']['id'] = $user['User']['id'];
				$this->request->data['User']['password'] = $password;
				if ($this->User->save($this->request->data)) {
					$contact_email = $this->SiteSetting->find('first', array('conditions' => array('SiteSetting.id' => 1), 'fields' => array('SiteSetting.admin_email')));
					if($contact_email){
						$adminEmail = $contact_email['SiteSetting']['admin_email'];
					} else {
						$adminEmail = 'superadmin@Thoughtful.nl';
					}

					App::uses('CakeEmail', 'Network/Email');

					$Email = new CakeEmail();
					/* pass user input to function */
					$Email->emailFormat('both');
					$Email->from(array($adminEmail => 'Thoughtful'));
					$Email->to($user['User']['email']);
					$Email->subject('Thoughtful Forgot Password');
					$Email->send($msg_body);

					$this->Session->setFlash(__('Your new password has been sent to your email.'));
					return $this->redirect(array('action' => 'admin_forgotpassword'));
				} else {
					$this->Session->setFlash(__('Your details could not be saved. Please, try again.'));
				}
			} else {
				$this->Session->setFlash(__('Invalid email provided. Please, try again.'));
			}
		}
	}
	
	public function getCountryname($id = null){
		$countryname = '';
		if($id!=''){
			$countryName = $this->Country->find('first', array('conditions' => array('Country.id' => $id), 'fields' => array('Country.printable_name')));
			if($countryName){
				$countryname = $countryName['Country']['printable_name'];
			}
		}
		return $countryname;
	}
	
function captcha()	
{
	$this->autoRender = false;
	$this->layout='ajax';
	if(!isset($this->Captcha))	
	{ 
		$this->Captcha = $this->Components->load('Captcha', array(
			'width' => 150,
			'height' => 50,
			'theme' => 'default', 
		)); 
	}
	$this->Captcha->create();
}	
	
	
public function signup($referrer_id = null) {
         
       $this->loadModel('Zone');
		$userid = $this->Session->read('Auth.User.id');
		$email = $this->Session->read('Auth.User.email');
		$username = $this->Session->read('Auth.User.username');
		if(isset($userid) && $userid!=''){
			return $this->redirect(array('action' => 'profile',$username));
		}
		$title_for_layout = 'Sign in | Sign up';
		$this->layout=false;
		$time_zones=$this->Zone->find("all",array('order'=>array('Zone.zone_name')));

		if ($this->request->is('post')) {
			
				$options = array('conditions' => array('User.email'  => $this->request->data['User']['email']));
				$emailexists = $this->User->find('first', $options);
				if(!$emailexists)
				{
					if($this->request->data['User']['password']==$this->request->data['User']['conpassword']){
						$this->request->data['User']['username']=$this->request->data['User']['name'].rand();
						
						$this->request->data['User']['registration_date'] = date('Y-m-d');
						
                                                $this->request->data['User']['password_txt']=$this->request->data['User']['password'] ;

						$this->User->create();
						
						if ($this->User->save($this->request->data)) {
							#pr($this->request->data['User']['subscribe_newsletter']);//exit;
							
							$contact_email = $this->SiteSetting->find('first', array('conditions' => array('SiteSetting.id' => 1), 'fields' => array('SiteSetting.admin_email')));
							if($contact_email){
								$adminEmail = $contact_email['SiteSetting']['admin_email'];
							} else {
								$adminEmail = 'thoughtful@info.com';
							}

							$options = array('conditions' => array('User.id' => $this->User->getLastInsertId()));
							$lastInsetred = $this->User->find('first', $options);
							$SITE_URL=Configure::read("SITE_URL");
							$link = "<a href=".$SITE_URL.'/users/confirm/'.base64_encode($lastInsetred['User']['id']).">Click Here</a>";
							$this->loadModel("EmailTemplate");
							$email_template=$this->EmailTemplate->find("first",array("conditions"=>array("EmailTemplate.id"=>1)));
							$msg_body=str_replace(array('[USER]','[LINK]'),array($this->request->data['User']['name'],$link),$email_template['EmailTemplate']['content']);
							$this->send_email($adminEmail,$this->request->data['User']['email'],$email_template['EmailTemplate']['subject'],$msg_body);
							

							
							
							  $this->Session->setFlash('Thanks for registering.You will receive a confirmation email shortly. Please activate your account by clicking on the activation link given in that.', 'default', array('class' => 'successmessage'));
							
							return $this->redirect(array('action' => 'signup'));
						} else {
							
							  $this->Session->setFlash(__('Sorry your details could not be saved. Please, try again.', 'default', array('class' => 'error')));
							
						}
					} else {
						
						  $this->Session->setFlash(__('Password and Confirm Password Mismatch. Please, try again.', 'default', array('class' => 'error')));
											}
				} else {
					
					  $this->Session->setFlash(__('Email already exists. Please, try another.', 'default', array('class' => 'error')));
					
				}
			
			
		}
		$this->set(compact('title_for_layout','time_zones'));
	}
public function checkemail($referrer_id = null) 
{
	$email=isset($_REQUEST['email'])?$_REQUEST['email']:'';
	$is_exist=$this->User->find("count",array("conditions"=>array("User.email"=>$email)));
	echo $is_exist;
	exit;
	
}
public function check_paypal_email($referrer_id = null) 
{
	$paypal=isset($_REQUEST['paypal'])?$_REQUEST['paypal']:'';
	$is_exist=$this->User->find("count",array("conditions"=>array("User.paypal_email"=>$paypal)));
	$is_exist=0;
	echo $is_exist;
	exit;
	
}

	public function confirm($id = null) {
		$id=  base64_decode($id);
                $this->loadModel('EmailShare');
                
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
                
                
                
		if ($id) {
			$this->request->data['User']['is_active'] = 1;
			$this->request->data['User']['id'] = $id;
			if ($this->User->save($this->request->data)) {		
                        $this->Session->setFlash('The account has been activated. You can now login', 'default', array('class' => 'successmsg'));
                        $user=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
                        $if_share=$this->EmailShare->find('count',array('conditions'=>array('EmailShare.email'=>$user['User']['email'])));
			if($if_share>0)
                        {
                        return $this->redirect(array('controller'=>'scores','action' => 'stakes'));
                        }
                        else 
                        {
                        return $this->redirect(array('action' => 'login'));
                        }
			} else {
				
                   $this->Session->setFlash(__('<center>The user could not be activated. Please, try again.</center>'));
				
				return $this->redirect(array('action' => 'index'));
			}
		} 
	}

    public function emailExists($email = null) {
		$data = '';
		if($email){
			$emailexists = $this->User->find('first',array('conditions' => array('User.email'=>$email),'fields' => array('User.id')));
			if($emailexists){
                  $data = 'Email already exists. Please try another.';
			} else {
				$data = '';
			}
		}
		echo $data;
		exit;
	}

	/*public function usernameExists($email = null) {
		$siteLang=$this->Session->read('languageSite');
		$data = '';
		if($email){
			$usernameexists = $this->User->find('first',array('conditions' => array('User.email'=>$email),'fields' => array('User.id')));
			if($usernameexists){
				if($siteLang=='de')
				{
				  $data = 'Benutzername ist bereits vorhanden. Bitte versuchen Sie einen anderen.';
				}
				else
				{
				  $data = 'Username already exists. Please try another.';
				}
			} else {
				$data = '';
			}
		}
		echo $data;
		exit;
	}*/

	public function login() {
		$userid = $this->Session->read('Auth.User.id');
		$email = $this->Session->read('Auth.User.email');
		$username = $this->Session->read('Auth.User.username');
		if(isset($userid) && $userid!=''){
                    return $this->redirect(array('controller'=>'scores','action'=>'feed_wall'));  
		}
		$user=$this->User->find("first",array("conditions"=>array()));
		
		$this->layout=false;
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
			$is_active = $this->Session->read('Auth.User.is_active');
                        if($is_active==1)
                        {
                        $this->Session->write('flogin', 1);
                        $this->Session->setFlash('You have been successfully logged in', 'default', array('class' => 'successmsg'));					
                        return $this->redirect($this->Auth->redirect());
                        }
                        else
                        {
                            $this->Session->setFlash(__('You are not active user', 'default', array('class' => 'error'))); 
                            return $this->redirect(array('action' => 'logout'));
                            
                        }
			
			}
			else
			{
			$this->Session->setFlash(__('Invalid email or password, try again', 'default', array('class' => 'error')));
			return $this->redirect(array('action' => 'login'));
			}
		}
	}
	
	public function logout() {
		if($this->Auth->logout()){
		  $this->redirect(array('action' => 'index'));
                  //$this->redirect( Router::url( $this->referer(), true ) );  
		}
	}
	
	

	public function profile($score_id=null) {
		$this->loadModel('Zone');
		$this->loadModel('Score');
		if($this->request->is("post"))
		{
		$paypal_email=$this->request->data['User']['paypal_email']	;
		$id=$this->request->data['User']['id']	;
                if(empty($this->request->data['User']['password']))
                {
                   $this->request->data['User']['password']=$this->request->data['User']['password_txt'] ;
                }  else {
                   $this->request->data['User']['password_txt']=$this->request->data['User']['password'] ;
                }
                
		$count=$this->User->find('count',array('conditions'=>array('User.paypal_email'=>$paypal_email,'User.id !='=>$id)));
		$count=0;
		if($count>0)
		{
			$this->Session->setFlash(__('Duplicate Paypal email'));
		}
		else{
			if(isset($this->request->data['User']['profile_image']) && $this->request->data['User']['profile_image']['name']!=''){
				$ext = explode('/',$this->request->data['User']['profile_image']['type']);
				if($ext){
					$uploadFolder = "user_images";
					$uploadPath = WWW_ROOT . $uploadFolder;
					$extensionValid = array('jpg','jpeg','png','gif');
					if(in_array($ext[1],$extensionValid)){
						$imageName = (strtolower(trim($this->request->data['User']['profile_image']['name'])));
						$full_image_path = $uploadPath . '/' . $imageName;
						move_uploaded_file($this->request->data['User']['profile_image']['tmp_name'],$full_image_path);
						$this->request->data['User']['profile_image'] = $imageName;
						
					} else{
						$this->Session->setFlash(__('Please uploade image of .jpg, .jpeg, .png or .gif format.'));
					}
				}
			}
			else {
			$this->request->data['User']['profile_image'] = $this->request->data['User']['hide_photo'];
			}
			
			
			$this->User->create();

			if($this->User->save($this->request->data))
			{
			
                         if(!empty($score_id))   
                         {
                         $this->redirect(array('controller' => 'scores','action'=>'confirm',$score_id));
 
                         }
                         
                                                     
			 $this->Session->setFlash('Your profile has been changed successfully.','default',array('class'=>'successmsg'));
			}
		}
		
		}
		else
		{
		//echo $this->Session->read('Auth.User.id');exit;
		$this->request->data=$this->User->find("first",array("conditions"=>array("User.id"=>$this->Session->read('Auth.User.id'))));
                if($score_id!='' and   $this->request->data['User']['name']!='' and $this->request->data['User']['time_zone']!='')
                {
                $is_valid_judge=$this->Score->find("count",array("conditions"=>array('Score.judge_email'=>$this->request->data["User"]["email"])));     
                if($is_valid_judge>0)    
                {
                 $this->redirect(array('controller' => 'scores','action'=>'confirm',$score_id));
                }
                else 
                {
		$this->Session->setFlash(__('You are not judged'));
                $this->redirect(array('controller' => 'users','action'=>'logout'));
                }     
                }                    
		}
		$time_zones=$this->Zone->find("all",array('order'=>array('Zone.zone_name')));

		$this->set(compact('title_for_layout','user','countryname','userid','startups','funds','currencies','follows','time_zones','last_score'));
	}
	
	
	
        public function change_profile_img() {
		if($this->request->is("post"))
		{
		if(isset($this->request->data['User']['profile_image']) && $this->request->data['User']['profile_image']['name']!=''){
		$pathpart=pathinfo($this->request->data['User']['profile_image']['name']);
		$ext=$pathpart['extension'];	
				if($ext){
					$uploadFolder = "user_images";
					$uploadPath = WWW_ROOT . $uploadFolder;
					$extensionValid = array('jpg','jpeg','png','gif');
					if(in_array(strtolower($ext),$extensionValid)){
						$imageName = time().'.'.$ext[1];
						$full_image_path = $uploadPath . '/' . $imageName;
						move_uploaded_file($this->request->data['User']['profile_image']['tmp_name'],$full_image_path);
						$this->request->data['User']['profile_image'] = $imageName;
						
					} else{
						$this->Session->setFlash(__('Please uploade image of .jpg, .jpeg, .png or .gif format.'));
                                                $this->redirect( Router::url( $this->referer(), true ) );
					}
				}
			}
			else {
				$this->request->data['User']['profile_image'] = $this->request->data['User']['hide_photo'];
			}
			//pr($this->request->data); exit;
			//$this->User->create();

			if($this->User->save($this->request->data))
			{
                         $this->redirect( Router::url( $this->referer(), true ) );
                          
			}
		}
		else
		{
			$this->request->data=$this->User->find("first",array("conditions"=>array("User.id"=>$this->Session->read('Auth.User.id'))));
		}
                exit;
		$this->set(compact('title_for_layout','user','countryname','userid','startups','funds','currencies','follows'));
		
		
		
	}	
	
	function changepwd()
	{
		if($this->request->is("post"))
		{
			
			if($this->request->data['User']['password']!=$this->request->data['User']['confirm_password'])
			{
			$this->Session->setFlash(__("Confirm password doe's not match."));
			}
			else{
			$this->User->create();

			if($this->User->save($this->request->data))
			{
			 $this->Session->setFlash('Your password changed successfully.','default',array('class'=>'successmsg'));
			}
	
			}
			
	}
	}
	
	
	
	


	public function editprofile() {
		#$siteLang=$this->Session->read('languageSite');
		$title_for_layout = 'Edit Profile';
		$countryname = '';
		$email = $this->Session->read('Auth.User.email');
		$userid = $this->Session->read('Auth.User.id');
		if(!isset($userid)){
			$this->redirect('/');
		}
		if (!$this->User->exists($userid)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			#pr($this->request->data);
			#exit;
			if(isset($this->request->data['User']['avatar']) && $this->request->data['User']['avatar']['name']!=''){
				$ext = explode('/',$this->request->data['User']['avatar']['type']);
				if($ext){
					$uploadFolder = "user_images";
					$uploadPath = WWW_ROOT . $uploadFolder;
					$extensionValid = array('jpg','jpeg','png','gif');
					if(in_array($ext[1],$extensionValid)){
						$imageName = $this->request->data['User']['id'].'_'.(strtolower(trim($this->request->data['User']['avatar']['name'])));
						$full_image_path = $uploadPath . '/' . $imageName;
						move_uploaded_file($this->request->data['User']['avatar']['tmp_name'],$full_image_path);
						$this->request->data['User']['avatar'] = $imageName;
						#exit;
						//unlink($uploadPath. '/' .$this->request->data['User']['hidprofile_img']);
					} else{
						$this->Session->setFlash(__('Please uploade image of .jpg, .jpeg, .png or .gif format.'));
						}
				}
			} else {
				$this->request->data['User']['avatar'] = $this->request->data['User']['hidprofile_img'];
			}
			if ($this->User->save($this->request->data)) {
				
				   $this->Session->setFlash(__('Your details have been saved.'));
				 
				return $this->redirect(array('action' => 'editprofile'));
			} else {
				$this->Session->setFlash(__('Your details could not be saved. Please, try again.'));
				}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $userid));
			$this->request->data = $this->User->find('first', $options);
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $userid));
			$user = $this->User->find('first', $options);
			if(isset($this->request->data['User']['country']) && $this->request->data['User']['country']!=0){
				$countryname = $this->Country->find('first',array('conditions' => array('Country.id'=>$this->request->data['User']['country']),'fields' => array('Country.printable_name')));
				#pr($countryname);
				$countryname = $countryname['Country']['printable_name'];
			}
			#pr($this->request->data);
		}
		$countries = $this->Country->find('list',array('fields' => array('Country.printable_name'),'order'=>array('Country.printable_name ASC')));
		$this->set(compact('title_for_layout','countries','countryname','userid','user'));
	}


	public function forgotpassword(){
		
		
                $this->layout=false;
                $this->loadModel('EmailTemplate');
		if ($this->request->is(array('post', 'put'))) {
			$options = array('conditions' => array('User.email' => $this->request->data['User']['email']));
			$user = $this->User->find('first', $options);			
			if($user){
				
                                $password=rand();
				$this->request->data['User']['id'] = $user['User']['id'];
				$this->request->data['User']['password'] = $password;
                                $this->request->data['User']['password_txt'] = $password;
                                $this->request->data['User']['date_of_birth'] = $user['User']['date_of_birth'];
                                $this->request->data['User']['is_active'] = $user['User']['is_active'];
                                $this->request->data['User']['is_subscribed'] = $user['User']['is_subscribed'];
                                $this->request->data['User']['registration_date'] = $user['User']['registration_date'];
                                
					$contact_email = $this->SiteSetting->find('first', array('conditions' => array('SiteSetting.id' => 1), 'fields' => array('SiteSetting.admin_email')));
					if($contact_email){
						$adminEmail = $contact_email['SiteSetting']['admin_email'];
					} else {
						$adminEmail = 'info@Thoughtful.com';
					}
                                        
                                if($this->User->save($this->request->data)) 
                                {
                                  $tpl=$this->EmailTemplate->find("first",array("conditions"=>array("EmailTemplate.id"=>15)));
                                        $msg_body=str_replace(array('[USER]','[PASSWORD]'), array($user['User']['name'],$password), $tpl['EmailTemplate']['content']);

                                         $this->send_email($adminEmail,$this->request->data['User']['email'],$tpl['EmailTemplate']['subject'],$msg_body);
					
					 $this->Session->setFlash('Your new password has been sent to your email.','default',array('class'=>'successmsg'));  
                                }   
                                        
					 
				
			} else {
				
				   $this->Session->setFlash(__('Invalid email provided. Please, try again.'));
				
			}
		}
	}

	public function settings() {
		//$siteLang=$this->Session->read('languageSite');

		$title_for_layout = 'Account Settings';
		$countryname = '';
		$email = $this->Session->read('Auth.User.email');
		$userid = $this->Session->read('Auth.User.id');
		if(!isset($userid)){
			$this->redirect('/');
		}
		if (!$this->User->exists($userid)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			#pr($this->data);
			#exit;
			if(isset($this->request->data['User']['password11']) && $this->request->data['User']['password11']!=''){
				$this->request->data['User']['password'] = $this->request->data['User']['password11'];
			} else {
			}
			
			if ($this->User->save($this->request->data)) {
				
				  $this->Session->setFlash(__('Profile settings have been updated successfully.'));
				
				return $this->redirect(array('action' => 'settings'));
			} else {
				
                 $this->Session->setFlash(__('Your details could not be saved. Please, try again.'));
				
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $userid));
			$this->request->data = $this->User->find('first', $options);
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $userid));
			$user = $this->User->find('first', $options);
			if(isset($this->request->data['User']['country']) && $this->request->data['User']['country']!=0){
				$countryname = $this->Country->find('first',array('conditions' => array('Country.id'=>$this->request->data['User']['country']),'fields' => array('Country.printable_name')));
				$countryname = $countryname['Country']['printable_name'];
			}
		}
		$countries = $this->Country->find('list',array('fields' => array('Country.printable_name')));
		$this->set(compact('title_for_layout','countries','countryname','userid','user'));
	}
	
	
	
	
	public function contact(){
	if(!isset($this->Captcha))	
	{   
		$this->Captcha = $this->Components->load('Captcha');
	}
	$this->User->setCaptcha($this->Captcha->getVerCode());
		if ($this->request->is(array('post', 'put'))) {
				
			$captcha = $this->User->getCaptcha();	
				//echo $captcha;echo ' '.$this->request->data['User']['captcha'];exit;
			if($captcha==$this->request->data['User']['captcha'])
			{		
					
					$contact_email = $this->SiteSetting->find('first', array('conditions' => array('SiteSetting.id' => 1), 'fields' => array('SiteSetting.admin_email')));
							if($contact_email){
								$adminEmail = $contact_email['SiteSetting']['admin_email'];
							} else {
								$adminEmail = 'nits.sandeeptewary@gmail.com';
							}
							$frommail=$this->request->data['email'];
							$name=$this->request->data['name'];
							$body=$this->request->data['message'];
							
							$table = '<table style="width:400px;border:0px;">
							<tr>
								<td style="width:100px;">Name&nbsp;:</td>
								<td>'.$name.'</td>
							</tr>
							<tr>
								<td style="width:100px;">Email From&nbsp;:</td>
								<td>'.$frommail.'</td>
							</tr>
							<tr>
								<td style="width:100px;">Message&nbsp;:</td>
								<td>'.$body.'</td>
							</tr>
							</table>';
				$msg_body = 'Hi Admin,<br/><br/>New Contact Message . Details of the message: <br/>'.$table.' <br/><br/>Thanks,<br/>Thoughtful';
							

							App::uses('CakeEmail', 'Network/Email');

							$Email = new CakeEmail();
							
							$Email->emailFormat('both');
							$Email->from(array($frommail => 'Thoughtful'));
							$Email->to($adminEmail);
							$Email->subject('Thoughtful Contact Message');
							$Email->send($msg_body);
                            
							
							  $this->Session->setFlash(__('Your Message Send Successfully', 'default', array('class' => 'success')));
					   
			}
			else{
				$this->Session->setFlash(__('Captcha Mismatch, Please try again!', 'default', array('class' => 'error')));
				return $this->redirect(array('action' => 'contact'));
			}
		}
	}
	

function how_log_ago($timestamp=null)
        {
        date_default_timezone_set('UTC');

        $timestamp=  base64_decode($timestamp);
        
        
        
        
	if(!isset($timestamp) ||  empty($timestamp)) return false;

	$difference = time() - strtotime($timestamp);

if($difference < 60)
    return $difference." seconds ago";
else{
    $difference = round($difference / 60);
    if($difference < 60)
	return $difference." minute(s) ago";
    else{
	$difference = round($difference / 60);
	if($difference < 24)
	    return $difference." hour(s) ago";
	else{
	    $difference = round($difference / 24);
	    if($difference < 7)
		return $difference." day(s) ago";
	    else{
		$difference = round($difference / 7);
		return $difference." week(s) ago";
	    }
	}
    }
}				
}

function browser_timezone()
{
 $time_zone=  isset($_REQUEST['timezone'])?$_REQUEST['timezone']:'';    
 $this->Session->write('time_zone',$time_zone);   
 exit;
 
 
}

public function fblogin(){
	 $fb_user_id=  isset($_REQUEST['fb_user_id'])?$_REQUEST['fb_user_id']:'';
	 $fb_first_name=  isset($_REQUEST['fb_first_name'])?$_REQUEST['fb_first_name']:'';
	 $fb_last_name=  isset($_REQUEST['fb_last_name'])?$_REQUEST['fb_last_name']:'';
	 $fb_email=  isset($_REQUEST['fb_email'])?$_REQUEST['fb_email']:'';
	 $count=$this->User->find('count',array('conditions'=>array('User.fbuserid'=>$fb_user_id)));
	 if($count<=0)
	 {
	 $count1=$this->User->find('count',array('conditions'=>array('User.email'=>$fb_email)));
	 if($count1<=0)
	 {    
	 $pass=rand();    
	 $data['User']['fbuserid']=$fb_user_id;    
	 $data['User']['first_name']=$fb_first_name;    
	 $data['User']['last_name']=$fb_last_name;  
	 $data['User']['name']=$fb_first_name.' '.$fb_last_name;  
	 $data['User']['email']=$fb_email; 
	 $data['User']['password']=$pass ;
	 $data['User']['password_txt']=$pass ;
	 $data['User']['is_active']=1 ;
	 $this->User->save($data);
	 $output['email']=$fb_email;
	 $output['pass']=$pass;
	 $output['Ack']=1;    
	 }else{
	 $output['Ack']=0;    
	 }
	 }else{
	 $user=$this->User->find('first',array('conditions'=>array('User.fbuserid'=>$fb_user_id)));
	 $output['email']=$user['User']['email'];
	 $output['pass']=$user['User']['password_txt'];
	 $output['Ack']=1;    
	 }
	 echo json_encode($output);
	 exit;
 
 }



  public function twitter_login()
        {

        	//echo "hello";exit;
            $CONSUMER_KEY = Configure::read('TWITTER_CONSUMER_KEY');
            $CONSUMER_SECRET = Configure::read('TWITTER_CONSUMER_SECRET');
            $OAUTH_CALLBACK=Configure::read('SITE_URL').'/users/twitter_login/';
            require_once(ROOT.'/app/Vendor' . DS . 'twitteroauth/twitteroauth.php');
            //App::import('Vendor', 'twitteroauth/twitteroauth.php');
            //App::import('Vendor', 'twitteroauth', array('file' => 'twitteroauth.php'));

            if(isset($_GET['oauth_token']))
            {
            	//echo $_GET['oauth_token'];exit;
                $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $this->Session->read('request_token'), $this->Session->read('request_token_secret'));
                $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
                //print_r($access_token);exit;
                if($access_token)
                {

                    $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
                    $params =array();
                    $params['include_entities']='false';
                    $content = $connection->get('account/verify_credentials',$params);

                    if($content && isset($content->screen_name) && isset($content->name))
                    {
                            //$_SESSION['name']=$content->name;
                            //$_SESSION['image']=$content->profile_image_url;
                            //$_SESSION['twitter_id']=$content->screen_name;
                        $check_exits = $this->User->find('first',array('conditions' => array('User.tw_user_id' => $content->id)));
                        /*if(!empty($check_exits))
                        {

                            //$this->Session->write('userid', $check_exits['User']['id']);
                            //$this->Session->write('username', $check_exits['User']['first_name']);
                            
                            $user_data_auth['User']['id']=$check_exits['User']['id'];
                            $user_data_auth['User']['is_login']=1;

                            //$this->Auth->login($userdetails);
                            
                            $this->Session->setFlash(__('Login Successful.', 'default', array('class' => 'success')));
                            
                            //$post_errand = $this->Session->read('post_errand');
                            
                            $this->redirect(array('action' => 'profile'));
                        }else{*/
                        	//echo "hello";exit;
                            $details = array();
                            if(!empty($content->name))
                            {
                                $name = explode(" ", $content->name);
                                $details['first_name'] = (!empty($name['0'])?$name['0']:'');
                                $details['last_name'] = (!empty($name['1'])?$name['1']:'');
                            }
                            $details['tw_user_id'] = $content->id;
                            $details['username'] = $content->screen_name;
                            $details['tw_verification'] = $content->id;
                            $details['password'] = $content->id;
                            $details['password_txt'] = $content->id;
                            $details['join_date'] = date('Y-m-d');
                            $details['is_active'] = 1;
                            $details['User']['is_login']=1;
                            $details['name']=$details['first_name'].' '.$details['last_name'];
                            //$this->User->save($details);
                            //$this->redirect(array('action' => 'profile'));
                            $this->Session->write('twitter_details',$details);       
                            $this->redirect(array('action' => 'set_email'));
                        //}
                            //redirect to main page.
                            //header('Location: login.php'); 

                    }
                    else
                    {
                            echo "<h4> Login Error </h4>";
                            exit;
                    }
                }
            }
            else
            {

                $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
                $request_token = $connection->getRequestToken($OAUTH_CALLBACK);
                //print_r($request_token); 
                //exit;

                if($request_token)
                {
                    $token = $request_token['oauth_token'];
                    //echo $token;
                    $this->Session->write('request_token',$token);
                    $this->Session->write('request_token_secret',$request_token['oauth_token_secret']);
                    $url = $connection->getAuthorizeURL($token);
                    $this->redirect($url);
                }
                else //error receiving request token
                {
                        echo "Error Receiving Request Token";
                        exit;
                }
            }
        }



        public function set_email()
        {
            $details = $this->Session->read('twitter_details');
            if(empty($details))
            {
                $this->redirect(array('action' => 'login'));
            }
            else
            {
                if($this->request->is('post'))
                {
                    $email = $this->request->data['User']['email'];
                    if(!empty($email))
                    {
                        $if_exist = $this->User->find('first',array('conditions' => array('User.email' => $email)));
                        if(empty($if_exist))
                        {
                            $user_data = array();
                            $user_data['User'] =  $details;
                            $user_data['User']['email'] = $email;
                            $user_data['User']['password'] = $details['password'];
                            $user_data['User']['name'] = $details['name'];
                           // $this->request->data['User']['email'] = $email;
                            //$this->request->data['User']['password'] = $details['password'];
                            if($this->User->save($user_data))
                            {

                            	if($this->Auth->login())
                             {
                             	$this->redirect(array('action' => 'profile'));

                             }
                                $this->Session->delete('twitter_details');
                                //$this->Session->write('userid', $this->User->getInsertID());
                                //$this->Session->write('username', $details['first_name']);
                                 
                                /*$contact_email = $this->SiteSetting->find('first', array('conditions' => array('SiteSetting.id' => 1), 'fields' => array('SiteSetting.contact_email', 'SiteSetting.site_name')));
                                if($contact_email){
                                        $adminEmail = $contact_email['SiteSetting']['contact_email'];
                                } else {
                                        $adminEmail = 'superadmin@abc.com';
                                }
                                $options = array('conditions' => array('User.id' => $this->User->getInsertID()));
                                $lastInsetred = $this->User->find('first', $options);

                                $this->loadModel('EmailTemplate');
                                $EmailTemplate=$this->EmailTemplate->find('first',array('conditions'=>array('EmailTemplate.id'=>4)));
                                $siteurl= Configure::read('SITE_URL');
                                $LOGINLINK=$siteurl.'users/login';
                                $msg_body =str_replace(array('[USER]','[LOGINLINK]'),array($lastInsetred['User']['first_name'],$LOGINLINK),$EmailTemplate['EmailTemplate']['content']);*/

                                /*App::uses('CakeEmail', 'Network/Email');

                                $Email = new CakeEmail();
                                $Email->emailFormat('both');
                                $Email->from(array($adminEmail => $contact_email['SiteSetting']['site_name']));
                                $Email->to($lastInsetred['User']['email']);
                                $Email->subject('Welcome to '.$contact_email['SiteSetting']['site_name']);
                                $Email->send($msg_body);*/
                                
                                /*$this->loadModel('InboxMessage');
                                $InboxMessageData['InboxMessage']['location'] ='Broshure.pdf';
                                $InboxMessageData['InboxMessage']['user_id'] = $this->User->getInsertID();
                                $InboxMessageData['InboxMessage']['sender_id'] = 2;
                                $InboxMessageData['InboxMessage']['contact_id'] = 1;
                                $InboxMessageData['InboxMessage']['subject'] = 'Welcome to Errand Champion.';
                                $InboxMessageData['InboxMessage']['message'] = 'Welcome to Errand Champion. Please refer to your email for a convenient overview of our client and contractor polices for your reference.';
                                $InboxMessageData['InboxMessage']['date_time'] = date('Y-m-d H:i:s');*/
                                //$this->request->data['InboxMessage']['parent_id']=$inboxMessage['InboxMessage']['parent_id'];
                                //$this->request->data['InboxMessage']['sentmsg_id']=$inboxMessage['InboxMessage']['sentmsg_id'];
                                //$this->request->data['InboxMessage']['contact_id']=$id;


                                /*$this->InboxMessage->create();
                                $this->InboxMessage->save($InboxMessageData);

                                $from=$contact_email['SiteSetting']['site_name'].' <'.$adminEmail.'>';
                                $Subject_mail='Welcome to '.$contact_email['SiteSetting']['site_name'];
                                $this->php_mail($lastInsetred['User']['email'],$from,$Subject_mail,$msg_body);*/
                                
                                $this->Session->setFlash(__('Login Successful.', 'default', array('class' => 'success')));
                                $this->redirect(array('action' => 'profile'));
                            }
                            else
                            {
                                $this->Session->setFlash(__('Internal error. Please try again.'));
                            }
                        }
                        else
                        {
                             //$this->request->data['User']['email'] = $email;
                             //$this->request->data['User']['password'] = $details['password'];
                             if($this->Auth->login())
                             {
                             	$this->redirect(array('action' => 'profile'));

                             }
                             $this->Session->delete('twitter_details');
                             
                            //$this->Session->setFlash(__('Email already exists. Pleas try another.'));
                        }
                    }
                    else {
                        $this->Session->setFlash(__('Please enter valid email.'));
                    }
                    //pr($details);
                    //exit;
                }
                else
                {
                    
                }
                $this->set(compact('title_for_layout','details'));
            }
        }



        public function gpluslogin()

{



                App::import('Vendor', 'Google_Client', array('file' => 'google-login-api'.DS.'src'.DS.'Google_Client.php'));

                App::import('Vendor', 'Google_Oauth2Service', array('file' => 'google-login-api'.DS.'src'.DS.'contrib'.DS.'Google_Oauth2Service.php'));



            $google_client_id 	= '80050910472-q9s5vhq4apcof5pevu0m5gdrb4sv2bai.apps.googleusercontent.com';

			$google_client_secret 	= 'nvbkEgOiemFa5_wSb67hBOOl';

			$google_redirect_url 	= 'http://scorestars.com/users/gpluslogin'; //path to your script

			$google_developer_key 	= 'AIzaSyBGLGp25ADiEGaeJHUn1yLZjYz6Z1nL25g';



                $gClient = new Google_Client();

                $gClient->setApplicationName('Login to Scorestars');

                $gClient->setClientId($google_client_id);

                $gClient->setClientSecret($google_client_secret);

                $gClient->setRedirectUri($google_redirect_url);

                $gClient->setDeveloperKey($google_developer_key);

                $google_oauthV2 = new Google_Oauth2Service($gClient);

                if(isset($_REQUEST['code']))

                {

                   $gClient->authenticate($_REQUEST['code']);

                   $_SESSION['token'] = $gClient->getAccessToken();



                   if (isset($_SESSION['token'])) 

                   { 

                         $gClient->setAccessToken($_SESSION['token']);

                   }

                   if ($gClient->getAccessToken()) 

                   {

                          $user 		        = $google_oauthV2->userinfo->get();

                          $user_id 		        = $user['id'];

                          $first_name           = $user['given_name'];

                          $last_name            = $user['family_name'];

                          $image                = $user['picture'];

                          $gender               = $user['gender'];

                          //$password             = $user['id'];

                          $email 		= filter_var($user['email'], FILTER_SANITIZE_EMAIL);

                          $_SESSION['token'] 	= $gClient->getAccessToken();

                   }

                 

                   unset($_SESSION['token']);

                   

                   #$SITE_URL = Configure::read('SITE_URL');

                    //$options = array('conditions' => array('User.google_user_id'  => $user_id));

                    //$googleexists = $this->User->find('first', $options);

                    #pr($googleexists);

                    #exit;

                    /*if(!empty($googleexists))

                    {

                            /*$this->Session->write('userid', $googleexists['User']['id']);

                            $this->Session->write('username', $googleexists['User']['username']);

                            $this->Session->write('is_admin', $googleexists['User']['admin']);

                            $this->Session->setFlash(__('Login Successful.', 'default', array('class' => 'success')));

                            return $this->redirect(array('controller'=>'products', 'action' => 'home'));*/

                        /* $this->request->data['User']['password'] = 'asd@123';

					     $this->request->data['User']['email'] = $googleexists['User']['email'];

					if ($this->Auth->login()) {

									//$this->Session->setFlash('You have been successfully logged in', 'success');

									//echo json_encode(array('status' => true, 'message' => 'You have been successfully logged in','url' => $this->webroot.'users/index'));

									//return $this->redirect(array('action' => 'admin_dashboard'));

									//return $this->redirect($this->Auth->redirect());
						            $this->redirect(array('action' => 'profile'));

								}

								else

								{

									$this->Session->setFlash('Not Successfully logged in.', 'error');

									//echo json_encode(array('status' => false,'message' => 'Invalid email or password, try again.'));

									return $this->redirect(array('action' => 'login'));

								}

                    } */

                    //else

                    //{

                        $options = array('conditions' => array('User.email'  => $email));

                        $emailexists = $this->User->find('first', $options);

                        if(!$emailexists)

                        {



                              //$this->request->data['User']['password'] = 'asd@123';
                        	  $this->request->data['User']['password'] = $user_id;

						       $this->request->data['User']['first_name'] = $first_name;

                                $this->request->data['User']['last_name'] = $last_name;

                                $this->request->data['User']['name'] = $first_name.' '.$last_name;

                                $this->request->data['User']['email'] = $email;

                                $this->request->data['User']['username'] = $this->request->data['User']['first_name'].rand();

                                //$this->request->data['User']['google_user_id'] = $user_id;

                                $this->request->data['User']['registration_date'] = date('Y-m-d');

						        $this->request->data['User']['is_active'] = 1;

							// $this->request->data['User']['password'] = md5($this->request->data['User']['password']);

							if($this->User->save($this->request->data))

							{

								if ($this->Auth->login()) {

										//$this->Session->setFlash('Vous avez été connecté avec succès', 'success');

										//return $this->redirect(array('action' => 'admin_dashboard'));

										//return $this->redirect($this->Auth->redirect());
									    $this->redirect(array('action' => 'profile'));

										//echo json_encode(array('status' => true,'message' => 'You have been successfully logged in','url' => $this->webroot.'users/index'));

									}

									else

									{

										$this->Session->setFlash('E Mail already exists. Please, try another.', 'error');

										// json_encode(array('status' => false,'message' => 'Invalid email or password, try again.'));

						

										return $this->redirect(array('action' => 'login'));

									}

							}

                                

                                

                        }

                        else

                        {

                              //$this->Session->setFlash(__('E Mail already exists. Please, try another', 'default', array('class' => 'error')));

                              //return $this->redirect(array('action' => 'index'));
                        	 //$this->request->data['User']['password'] = 'asd@123';
                        	 $this->request->data['User']['password'] = $user_id;

					         $this->request->data['User']['email'] = $email;

					        if ($this->Auth->login()) {

									//$this->Session->setFlash('You have been successfully logged in', 'success');

									//echo json_encode(array('status' => true, 'message' => 'You have been successfully logged in','url' => $this->webroot.'users/index'));

									//return $this->redirect(array('action' => 'admin_dashboard'));

									//return $this->redirect($this->Auth->redirect());
						            $this->redirect(array('action' => 'profile'));

								}

                        }

                //}

            }

    }






}	
