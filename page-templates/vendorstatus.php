<?php
/**
 * Template Name: Vendor Status
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); 

//$userid = $_REQUEST['userid'];
$userid = base64_decode($_REQUEST['userid']);
$usertype = $_REQUEST['type'];

$user_meta=get_userdata($userid);
//print_r($user_meta);
$user_roles=$user_meta->roles[0];
$userlogin = $user_meta->user_login;
$useremail = $user_meta->user_email;
?>

<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
					// Include the page content template.
					//get_template_part( 'content', 'page' );	
				?>
                	<div class="entry-content">
                        <?php the_content(); ?>
                        
                        <?php
							if($usertype=='Approve')
							{	
								$userid = base64_decode($_REQUEST['userid']);
								$u = new WP_User($userid);
								$u->remove_role($user_roles);
								$u->add_role('dc_vendor');	
								
								$body="";			
								$body .='<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="header" width="100%">
  <tbody>
    <tr>
      <td><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td bgcolor="#e4e4e4">�</td>
                    </tr>
                    <tr>
                      <td bgcolor="#007acc" height="8">�</td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="left-image" width="100%">
  <tbody>
    <tr>
      <td><table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                          <tbody>
                            <tr>
                              <td class="pad_01" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #585858; text-align:left; line-height:18px; border-bottom:1px solid #e5e5e5; padding:20px 0px 20px 0;"><img src="https://magnascent.com/images/magnascent.png" alt="Magnascent"></td>
                            </tr>
                            <tr>
                              <td class="pad_01" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #585858; text-align:left; line-height:18px; padding:27px 0px 10px 0;"><span style="font-family:Arial, Helvetica, sans-serif; font-size: 20px; color:#e21717 ; text-align:left; line-height:18px;font-weight:bold">Welcome to Magnascent</span></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="left-image" width="100%">
  <tbody>
    <tr>
      <td><table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                          <tbody>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 18px; color: #808080; text-align:left; line-height:18px;  padding:10px 0px 10px 0;"><span class="font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 16px; color:#808080; text-align:left; line-height:22px;">Congratulations! Your vendor application on Magnascent has been approved.</span></td>
                            </tr>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 18px; color: #808080; text-align:left; line-height:18px;  padding:10px 0px 10px 0;"><span class="font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 16px; color:#808080; text-align:left; line-height:22px;">Application status: Approved</span></td>
                            </tr>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 18px; color: #808080; text-align:left; line-height:18px;  padding:10px 0px 10px 0;"><span class="font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 16px; color:#808080; text-align:left; line-height:22px;">Applicant Username: '.$userlogin.'</span></td>
                            </tr>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 15px; color: #808080; text-align:left; line-height:18px;  padding:5px 0px 30px 0;">Thanks,<br/>The Magnascent Team<br/>magnascent.com</td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="header" width="100%">
  <tbody>
    <tr>
      <td><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td bgcolor="#007acc" height="8">�</td>
                    </tr>
                    <tr>
                      <td bgcolor="#e4e4e4">�</td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>';	
								
								//$dybcc = "eptdevs2013@gmail.com";
								// $dybcc = "jeffrey.winkles@gmail.com";	
								$dybcc = "";
								$url = 'https://api.sendgrid.com/';
								$user = 'magnascent';
								$pass = 'B9CK4ytDBLk9';
								
								$params = array(
									'api_user'  => $user,
									'api_key'   => $pass,
									'to'        => $useremail,	//'example3@sendgrid.com',
									'bcc'       => $dybcc,	//'example3@sendgrid.com',
									//'cc'      => $cc,
									'subject'   => 'Your account on Magnascent', //'testing from curl',
									'html'      => $body, 	 //'testing body',
									'fromname'  => 'Magnascent',
									'from'      => 'info@magnascent.com',   //'example@sendgrid.com',
								);			
								$request =  $url.'api/mail.send.json';
								
								// Generate curl request
								$session = curl_init($request);
								// Tell curl to use HTTP POST
								curl_setopt ($session, CURLOPT_POST, true);
								// Tell curl that this is the body of the POST
								curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
								// Tell curl not to return headers, but do return the response
								curl_setopt($session, CURLOPT_HEADER, false);
								curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
								
								// obtain response
								$response = curl_exec($session);
								curl_close($session);			
								// print everything out
								//print_r($response);
								?>
                                	<h4 class="text-success text-center alert alert-success">
                                    	Vendor application approved successfully!
                                    </h4>
                                <?php
							}
							if($usertype=='Reject')
							{
								$userid = base64_decode($_REQUEST['userid']);
								$u2 = new WP_User($userid);
								$u2->remove_role($user_roles);
								$u2->add_role('customer');	
								
								$userbody = '';
								$userbody = '<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="header" width="100%">
  <tbody>
    <tr>
      <td><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td bgcolor="#e4e4e4">�</td>
                    </tr>
                    <tr>
                      <td bgcolor="#007acc" height="8">�</td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="left-image" width="100%">
  <tbody>
    <tr>
      <td><table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                          <tbody>
                            <tr>
                              <td class="pad_01" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #585858; text-align:left; line-height:18px; border-bottom:1px solid #e5e5e5; padding:20px 0px 20px 0;"><img src="https://magnascent.com/images/magnascent.png" alt="Magnascent"></td>
                            </tr>
                            <tr>
                              <td class="pad_01" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #585858; text-align:left; line-height:18px; padding:27px 0px 10px 0;"><span style="font-family:Arial, Helvetica, sans-serif; font-size: 20px; color:#e21717 ; text-align:left; line-height:18px;font-weight:bold">Welcome to Magnascent</span></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="left-image" width="100%">
  <tbody>
    <tr>
      <td><table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                          <tbody>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 18px; color: #808080; text-align:left; line-height:18px;  padding:10px 0px 10px 0;"><span class="font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 16px; color:#808080; text-align:left; line-height:22px;">Thanks for creating an account with us on Magnascent. Unfortunately your request has been rejected.</span></td>
                            </tr>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 18px; color: #808080; text-align:left; line-height:18px;  padding:10px 0px 10px 0;"><span class="font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 16px; color:#808080; text-align:left; line-height:22px;">You may contact the site admin at <a href="mailto:info@magnascent.com">info@magnascent.com</a></span></td>
                            </tr>                                                        
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="header" width="100%">
  <tbody>
    <tr>
      <td><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td bgcolor="#007acc" height="8">�</td>
                    </tr>
                    <tr>
                      <td bgcolor="#e4e4e4">�</td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>';

								//$dybcc = "eptdevs2013@gmail.com";
								// $dybcc = "jeffrey.winkles@gmail.com";	
								$dybcc = "";								
								$url = 'https://api.sendgrid.com/';
								$user = 'magnascent';
								$pass = 'B9CK4ytDBLk9';
								
								$params = array(
									'api_user'  => $user,
									'api_key'   => $pass,
									'to'        => $useremail,	//'example3@sendgrid.com',
									'bcc'       => $dybcc,	//'example3@sendgrid.com',
									//'cc'      => $cc,
									'subject'   => 'Your account on Magnascent', //'testing from curl',
									'html'      => $userbody, 	 //'testing body',
									'fromname'  => 'Magnascent',
									'from'      => 'info@magnascent.com',   //'example@sendgrid.com',
								);			
								$request =  $url.'api/mail.send.json';
								
								// Generate curl request
								$session = curl_init($request);
								// Tell curl to use HTTP POST
								curl_setopt ($session, CURLOPT_POST, true);
								// Tell curl that this is the body of the POST
								curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
								// Tell curl not to return headers, but do return the response
								curl_setopt($session, CURLOPT_HEADER, false);
								curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
								
								// obtain response
								$response = curl_exec($session);
								curl_close($session);			
								// print everything out
								//print_r($response);
								?>
                                	<h4 class="text-success text-center alert alert-success">
                                    	Vendor application rejected successfully!
                                    </h4>
                                <?php
							}
						?>
                        
                    </div>
                <?php				
				endwhile;
			?>
            
            

		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php get_footer();