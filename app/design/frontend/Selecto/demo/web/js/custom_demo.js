require([
    'jquery',
    'mage/cookies'
],function ($) {
    $(document).ready(function () {
    	if($('#newsletter-validate-detail').length){
    		$('#newsletter-validate-detail').on('submit',function(){
    			if($('#newsletter-validate-detail').validation('isValid')){
    				alert('This is just a test');
    				return false;
    			}
    		});
    	}
    });
});