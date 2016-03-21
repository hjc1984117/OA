var customer_list;
var SalecountListViewModel = new function () {
    var self_ = this;
    //self_.list = ko.observable("list");
    self_.salecountList = ko.observableArray([]);
    self_.listSalecount = function (data) {
        ycoa.ajaxLoadGet("/api/sem/account.php", data, function (results) {
            self_.salecountList.removeAll();
            $.each(results.list, function (index, testdata) {
            	
                testdata.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3070303");
                testdata.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3070302");
                testdata.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3070304");
                self_.salecountList.push(testdata);
            });
   
            //----page info ----------------
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize,
                	searchName: $("#searchUserName").val(),searchChannel: $("#searchChannel").val(),searchProductType: $('#searchProductType').val(),
                	sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), 
                	searchName: $("#searchUserName").val(),searchChannel: $("#searchChannel").val(),searchProductType: $('#searchProductType').val(),
                	sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };

    self_.delSalecount = function (salecount) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                salecount.action = 3;
                ycoa.ajaxLoadPost("/api/sem/account.php", JSON.stringify(salecount), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({action: 1});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });

            }
        });
    };
    self_.setCustomer = function (salecount) {
        var html = "<div class='cu_div' id='doback_" + salecount.id + "'>";
        html += "<select name='nick_name' id='nick_name' style='width:300px;height:41px; ;float:left' class='form-control'>";
        $.each(customer_list, function (index, d) {
            html += "<option value='" + d.id + "'>" + (d.text) + "</option>";
        });
        html += "</select>";
        html += "<span class='input-group-addon' id='setCustomerOk' isTimely='" + (salecount.isTimely) + "' isQQTeach='" + (salecount.isQQTeach) + "' isTmallTeach_qj='" + (salecount.isTmallTeach_qj) + "' isTmallTeach_zy='" + (salecount.isTmallTeach_zy) + "' scheduledPackage='" + (salecount.scheduledPackage) + "' val='" + salecount.id + "' status='" + salecount.status + "' customer_id='" + salecount.customer_id + "'><i class='glyphicon glyphicon-ok' title='提交'></i></span>";
        html += "<span class='input-group-addon' id='setCancel' val='" + salecount.id + "' status='" + salecount.status + "'><i class='glyphicon glyphicon-remove' title='取消'></i></span>";
        html += "</div>";
        $("#customer_td_" + salecount.id).append(html);
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_" + salecount.id).show();
        $("#doback_" + salecount.id).animate({width: '382px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
    self_.showSalecount=function (salecount){
    	 $(".second_tr").hide();
         $(".submit_btn").hide();
         $(".cancel_btn").hide();
         $("#tr_" + salecount.id).show();
         //$("#submit_" + salecount.id).show();
         $("#cancel_" + salecount.id).show();
         if (!$("#form_" + salecount.id).attr('autoEditSelecter')) {
             initEditSeleter($("#form_" + salecount.id));
         }
         $("#tr_" + salecount.id + " input,#tr_" + salecount.id + " textarea").removeAttr("disabled");
    };
    self_.editSalecount = function (salecount) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + salecount.id).show();
        $("#submit_" + salecount.id).show();
        $("#cancel_" + salecount.id).show();
        if (!$("#form_" + salecount.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + salecount.id));
        }
        $("#tr_" + salecount.id + " input,#tr_" + salecount.id + " textarea").removeAttr("disabled");
    };
    
    self_.cancelTr = function (customer) {
        $("#tr_" + customer.id).hide();
        $("#submit_" + customer.id).hide();
        $("#cancel_" + customer.id).hide();
    };
}();
$(function () {
    ko.applyBindings(SalecountListViewModel, $("#dataTable")[0]);
    
    reLoadData({action: 1});
    initChannelAccount("channelaccounts","");
    
    $("#open_dialog_btn").click(function(){
    	$("#add_account_form #selectchannels").val("");
    	$("#add_account_form #userid").val("");
    	$("#add_account_form #username").val("");
        $("#add_account_form ul.auto_radio_ul li.auto_radio_li").removeClass("auto_radio_li_checked");
        $("#add_account_form .fa").removeClass("fa-check");
        $("#add_account_form .fa").removeClass("fa-warning");
    });
    
    //update a single line data
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var selectaccount=$("#"+formid+" .subselectaccounts").val();
        if(selectaccount.length > 0){
        	$("#"+formid+" .fa").addClass("fa-check");
        	var data = $("#" + formid).serializeJson();
            data.action = 2;
            data = JSON.stringify(data);
            ycoa.ajaxLoadPost("/api/sem/account.php", data, function (result) {
                if (result.code === 0) {
                    ycoa.UI.toast.success(result.msg);
                    reLoadData({action: 1});
                } else {
                    ycoa.UI.toast.error(result.msg);
                }
                ycoa.UI.block.hide();
            });
        }else{
        	$("#"+formid+" .fa").addClass("fa-warning");
        }
    });
    
    //add  single data
    $("#btn_submit_primary").click(function () {
        $("#add_account_form").submit();
    });
   
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1,sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), 
            searchChannel: $("#searchChannel").val(),searchProductType: $('#searchProductType').val(), searchName: $("#searchUserName").val()});
    });
    
    $("#dataTable").searchUserName(function (name) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),  searchName: name
        };
        reLoadData(data);
    });
     
   
   
    $("#add_account_form #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [9]}, function (data, el) {
            el.val(data.name);
            $("#add_account_form #userid").val(data.id);
        });
    });
    

    $("#testcontent").append('<input type="text" class="form-control accountlist" id="caccount" name="caccount" checkMore="true"/>');
    $(".accountlist").autoRadio(basedata_channels);
        
});
function reLoadData(data) {
    SalecountListViewModel.listSalecount(data);
}

function initEditSeleter(el) {
    var id=$(".ab", el).attr("id");
    var data=$(".ab", el).attr("data");
    initChannelAccount(id,data);
}

function initChannelAccount(boxid,initdata){
	var adata = {action: 2,initdata:initdata};
    ycoa.ajaxLoadGet("/api/sem/account.php", adata, function (results) {
    	
    	var initcontent='<div class="input-icon right"><i class="fa"></i><input type="hidden" name="ababab" id="ababab" value="" aria-required="true" aria-invalid="false"></div>';
    	initcontent+='<input type="hidden" class="subselectaccounts" value="'+initdata+'">';
    	$("#"+boxid).html(initcontent);
        
    	$.each(results, function (index, cdata) {
        	var id="caccount_"+boxid+"_"+cdata.id;
        	if(cdata.data != undefined){
        		var cacontent='<div class="clear">'+
        		'<p style="text-align:left;padding:5px 0;">'+cdata.name+'</p>'+
        		'<input type="text" class="form-control" id="'+id+'" value="'+cdata.initdata+'" name="caccount" checkMore="true"/>'+
        	'</div>';
	           $("#"+boxid).append(cacontent);
	           
	           $("#"+id).autoRadio(cdata.data,function(){
	        	   if(initdata==""){//新增
	        		    var clist=$("#add_account_form input[name=caccount]");
			           	var selectchannel="";
			           	$(clist).each(function(){
			           		var cs=$(this).val();
			           		if(cs !=""){
			           			selectchannel+=selectchannel==""?cs:","+cs;
			           		}
			           	});
			           	$("#selectchannels").val(selectchannel);
	        	   }else{
	        		   var clist=$("#"+boxid+" input[name=caccount]");
			           	var selectchannel="";
			           	$(clist).each(function(){
			           		var cs=$(this).val();
			           		if(cs !=""){
			           			selectchannel+=selectchannel==""?cs:","+cs;
			           		}
			           	});
			           	$("#"+boxid+" .subselectaccounts").val(selectchannel);
	        	   }
	        	  
	           });
        	}
        });
    });
}