<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <?php $this->load->view('elements/head'); ?>
</head>

<body>


        <?php $this->load->view('elements/header'); ?>


	<div id='mainContainer'>
           <div id="border">

                 <?php
                        if(isset($tpl))
                        {
                            $this->load->view($tpl);
                        }
                    ?>                
           <div>    
	</div>
    
<!-- footer start -->
    <?php $this->load->view('elements/footer'); ?>
    <!-- footer end -->
</body>
</html>