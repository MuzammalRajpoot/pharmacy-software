<?php

$cache_file = "product.json";
    header('Content-Type: text/javascript; charset=utf8');
?>
var productList = <?php echo file_get_contents($cache_file); ?> ; 

APchange = function(event, ui){
	$(this).data("autocomplete").menu.activeMenu.children(":first-child").trigger("click");
}
    function producstList() {
      
        $( ".productSelection" ).autocomplete(
		{
            source: productList,
			delay:300,
			focus: function(event, ui) {
				$(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);
				$(this).val(ui.item.label);
				return false;
			},
			select: function(event, ui) {
				$(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);
				$(this).val(ui.item.label);
				$(this).unbind("change");
				return false;
			}
		});
			$( ".productSelection" ).focus(function(){
				$(this).change(APchange);
			
			});
    }


