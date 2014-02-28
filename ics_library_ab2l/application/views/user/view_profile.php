<div class="cell body">
               
    </div>
    <div class="col">
        <div class="cell">
            <div class="col width-fill">
                <div class="col">
                   
                    <div class="cell panel">
                        <div id="regform" class="body">
                            <div class="cell">
                                <div class="color-red width-fill" style="font-weight: bold;"><p>
                                    <?php 
                                        if(isset($msg)){
                                            echo $msg;
                                         }

                                 ?>

                                  <h4><?php echo $name?></h4>
                                </div>
                               
                                <div class="col">
                                    <div class="cell">
                                        <?php echo validation_errors();
                                            if ($this->session->flashdata('success_username') != ''): 
                                                echo "<p>".$this->session->flashdata('success_username')."</p>"; 
                                            endif;   
                                            if ($this->session->flashdata('error_username1') != ''): 
                                                echo $this->session->flashdata('error_username1'); 
                                            endif; 
                                         ?>
                                    <span id="label_username">Username:</span><em id= "username"><?php echo  $user_details->username?></em><a id = "edit_username">Edit</a>
                                    
                                     
                                    <form id= 'form_username' method= 'post'  action = 'controller_editprofile/edit_username'>
                                    <span id="label_username1">Username:</span><input  type = 'text' id= 'input_username'name = 'new_username' required><span id = "helpusername"></span><br>
                                    <span>Enter password:</span><input type= "password" id ='pword_for_username' name ='pword_for_username'><br>
                                     <input type='button' id = "cancel_username" value= 'Cancel' required>
                                    <input type='submit' name = "sub" onclick= "return validate_username()" value= 'Save'><br>
                                    </form>
                                    

                                  
                                    <br/>
                                    <span>Classification:</span><em><?php echo  $user_details->classification?></em><br/>
                                    <span>College:</span><em><?php echo  $user_details->college?></em><br/>
                                    <span>Course:</span><em><?php echo  $user_details->course?></em><br/>
                                    <span id="label_email">Email:</span><em id= "email"><?php echo  $user_details->email?></em><a id = "edit_email">Edit</a><br>

                                    <form id= 'form_email' method= 'post' action = 'controller_editprofile/edit_email'>
                                    <span id="label_email1">Email Address:</span><input type = 'text' id= 'input_email'name = 'new_email' value="<?php echo  $user_details->email?>"><span id = "helpemail"></span><br>
                                    <span>Enter password:</span><input type= 'password' id ='pword_for_email' name ='pword_for_email'><br>
                                     <input type='button' id = "cancel_email" value= 'Cancel'>
                                    <input type='submit' disabled= "return false" value= 'Save'>
                                    </form>
                                    
                                    <span>Status:</span><em><?php echo  $user_details->status?></em><br/>

                            

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url() ?>js/validation.js"></script>
     <script >
     name = $("#username").text();
    success = "<?php echo $this->session->flashdata('success')?>";
    error_username="<?php echo $this->session->flashdata('error_username')?>";
  
    error_email = "";
        $( document ).ready(function(){   
       
          if(error_username == '' ){

                $('#form_username').hide();
                $('#form_email').hide();

          }
          else if( error_username== "error"){
                $('#form_username').show();
                $("#label_username").text("Edit Username");
                $("#username").hide();
                $('#form_email').hide();
                 $("#edit_username").hide();
          }
          else if(error_email== ""){
                $('#form_username').hide();
                $('#form_email').hide();
          }
          else{
                $('#form_email').show();
          }

         //if the edit username is clicked, the form for updating the username will be visible
         $("#edit_username").click(function(){

            
             $('#form_username').slideDown();

            $("#input_username").val(name);
            $("#label_username").text("Edit Username");
            $("#username").hide();
            $("#edit_username").hide();

        });
         //cancel edit username
         $("#cancel_username").click(function(){

            $('#form_username').slideUp();
            $("#label_username").text("Username:");
            $("#username").show();
            $("#edit_username").show();

        });
         //for checking if the new username already exist
          $('#input_username').on('blur', validate_new_un);


   window.validate_username = function() { 

        if($("#pword_for_username").val().trim()!= ""){
            return bool =validate_new_un();
        }
        else return false;
   }

    function validate_new_un(){

            //validation of the input
            msg= "Invalid input.";
            check= false;
                str= $('#input_username').val().trim();
                $('#input_username').val(str);
                if (str==""){ msg+="Username is required!";
                     $("#helpusername").text(msg);
                }
                else if(str==name){
                    msg+="Enter a new username."
                    $("#helpusername").text(msg);
                }
                else if (!str.match(/^[A-Za-z][A-Za-z0-9._]{2,20}$/)){
                    msg="Invalid characters.";
                
                }
                //if valid, check username availability
                else if(msg="Invalid input"){
                 msg="";
                 if(getResult(str)){
                    return true;
                 }
                 else return false;
                }
                //document.getElementsByName("valUser")[0].innerHTML=msg;
               
            //ajax for checking if the username already exist
            return false;  
           
    }        

    //to check if the new username is still available
    function getResult(name){
               // var baseurl = <?php echo base_url()?>;
               var bool= false;
                $('#helpusername').addClass('preloader');
                $("#helpusername").text("Checking availability...");
                $.ajax({
                    url : base_url + 'index.php/user/controller_editprofile/check_username/' + name,
                    cache : false,
                    async:false,
                    success : function(response){

                        $('#helpusername').delay(1000).removeClass('preloader');
                        if(response == 'userOk'){
                            $('#helpusername').removeClass('userNo').addClass('userOk');
                            $('#helpusername').text("Username available!");
                            
                          bool= true;
                        }
                        else{
                            $('#helpusername').removeClass('userOk').addClass('userNo');;
                            $("#helpusername").text("Username not available.");
                           bool= false;
                        }
                    }
                })

              
                return bool;

            }

         $("#edit_email").click(function(){

            
             $('#form_email').slideDown();

           
            $("#label_email").text("Edit email");
            $("#email").hide();
            $("#edit_email").hide();

        });
         //cancel edit email
         $("#cancel_email").click(function(){

             $('#form_email').slideUp();
            $("#label_email").text("Email Address:");
            $("#email").show();
            $("#edit_email").show();

        });
          

      });




     </script>
