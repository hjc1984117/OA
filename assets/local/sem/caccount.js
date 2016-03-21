var customer_list;
var current_Date;
var SalecountListViewModel = new function () {
    var self_ = this;
    //self_.list = ko.observable("list");
    self_.salecountList = ko.observableArray([]);
    self_.listSalecount = function (data) {
        ycoa.ajaxLoadGet("/api/sem/caccount.php", data, function (results) {
            self_.salecountList.removeAll();
            var currentDate = results.currentDate;
            current_Date = results.currentDate;
            
            $.each(results.list, function (index, testdata) {
            	
                testdata.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3070404");
                testdata.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3070402");
                testdata.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3070403");
            	
            	
            	
            	if(testdata.parent_flag==1){
            		testdata.parent_flag_name="主账号";
            		testdata.isParent=true;
            		testdata.isChild=false;
            	}else{
            		testdata.parent_flag_name="子账号";
            		testdata.isParent=false;
            		testdata.isChild=true;
            	}
            	
            	
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
                ycoa.ajaxLoadPost("/api/sem/caccount.php", JSON.stringify(salecount), function (result) {
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
    
    //update a single line data
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sem/caccount.php", data, function (result) {
            if (result.code === 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    
    //add  single data
    $("#btn_submit_primary").click(function () {
        $("#add_caccount_form").submit();
    });
   
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1,sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), 
            searchChannel: $("#searchChannel").val(),searchProductType: $('#searchProductType').val(), searchName: $("#searchUserName").val()});
    });
    
    
    $("#dataTable").sort(function (data) {
        var data = {
            action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchChannel: $("#searchChannel").val(),searchProductType: $('#searchProductType').val(), searchName: $("#searchUserName").val()
           
        };
        reLoadData(data);
    });
    
    $("#dataTable").searchUserName(function (name) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), 
            searchChannel: $("#searchChannel").val(),searchProductType: $('#searchProductType').val(), searchName: name
        };
        reLoadData(data);
    });
     
    $("#dataTable").searchAutoStatus(basedata_search_product_types, function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(), searchProductType: d.id, searchChannel:$("#searchChannel").val()
        });
    }, "类型", "searchProductType");
    
    $("#dataTable").searchAutoStatus(basedata_search_channels, function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(), searchProductType: $('#searchProductType').val(), searchChannel: d.id
        });
    }, "渠道", "searchChannel");
    
    
    $("#add_caccount_form #form_channel").autoRadio(basedata_channels);
    $("#add_caccount_form #form_product_type").autoRadio(basedata_product_types);
    $("#add_caccount_form #form_account_type").autoRadio(basedata_sem_account_types,function(d){
    	if(d.id == 1){
    		$(".parentaccount").hide();
    		$(".baseaccount").show();
    	}else{
    		$(".parentaccount").show();
    		$(".baseaccount").hide();
    	}
    });
    
    $("#open_dialog_btn").click(function(){
    	initAddForm();
    });
});

function initAddForm(){
	$("#form_parent_name").val("");
	$("#form_parent_id").val("");
	$("#form_account").val("");
	$("#form_token").val("");
	$("#form_pwd").val("");
	
	$(".parent_box").html('<input type="text" value="" class="form-control not-clear" readonly="true" name="parent_name" id="parent_name" placeholder="主账号"/><input type="hidden" value=""  name="parent_id" id="parent_id"/>');
	var parm={action:2};
    ycoa.ajaxLoadGet("/api/sem/caccount.php", parm, function (result) {
    	$("#form_parent_name").autoEditSelecter(result.list,function(d){
    		$("#form_parent_id").val(d.id);
    	});
    });
}
function reLoadData(data) {
    SalecountListViewModel.listSalecount(data);
}

function initEditSeleter(el) {
    $("#channel", el).autoRadio(basedata_channels);
    $("#product_type", el).autoRadio(basedata_product_types);
    var parm={action:2,fid:$(el).attr("pid")};
    ycoa.ajaxLoadGet("/api/sem/caccount.php", parm, function (result) {
    	$("#parent_name", el).autoEditSelecter(result.list,function(d){
    		 $("#parent_id", el).val(d.id);
    	});
    });
    el.attr('autoEditSelecter', 'autoEditSelecter');
}