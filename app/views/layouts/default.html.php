<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>

<!doctype html>
<html>
<head>
  <title>Getting Things Done with Engine Yard AppCloud</title>
  <?php echo $this->html->style(array('core', 'jqtabs')); ?>
  <link href="css/core.css" rel="stylesheet" type="text/css">
  <link href="css/jqtabs.css" rel="stylesheet" type="text/css">
  <?php echo $this->scripts(); ?>

</head>
<body>
    <div id="all">
        <div id="header">
            <div id="logo">
            	<a href="http://www.engineyard.com">Engine Yard</a>
            </div>
            <h1><span class="intro">Getting Things Done</span> deployed on <span class="branding">Engine Yard AppCloud</span></h1>
            <p class="sample">Sample Deployment Application</p>
        </div>
        <div id="content">
            <?php if(isset($alert)) { ?>
                <p id="alert">alert</p>
            <?php } ?>
			<?php echo $this->content(); ?>
			 </div>
				<div id="foot">
					<div class="copyright">
					<p>   
					  Running on Engine Yard: <a href="http://engineyard.com/products/appcloud">The Ruby Cloud</a> and <a href="http://www.rubyonrails.org">Rails 3</a><br>
					  Ruby is the Language of the Cloud.<br>
					  Copyright © Engine Yard, Inc. All rights reserved.
					</p>
					</div>
				</div>
			</div>
			</body>
			</html>
			
		