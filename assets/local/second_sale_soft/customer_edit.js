 var CustomerEditViewModel = new function () {
    var self_ = this;
    self_.operationType=1;
    self_.list = ko.observable("list");
    self_.customerEditList = ko.observableArray([]);
    //获取列表数据
    self_.listCustomerEdit = function (data) {     
        ycoa.ajaxLoadGet("/api/second_sale_soft/customer_edit.php", data, function (results) {
            self_.customerEditList.removeAll();  
            $.each(results.list, function(index, customerEdit) {
                customerEdit.show = true;
                self_.customerEditList.push(customerEdit);
            });
      //搜索
      ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize,searchName: $("#searchUserName").val(),sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(),searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    
     self_.cancelTr = function (customerEdit) {
       $("#tr_" + customerEdit.id).hide();
       $("#submit_button_"+customerEdit.id).hide();
   };
   
   self_.editCustomer = function(customerEdit)
   {
        $("#tr_"+customerEdit.id).show();
        $("#form_edit_"+customerEdit.id).show();
        $("#submit_button_"+customerEdit.id).show();
        $("#form_double_"+customerEdit.id).hide();
        $("#form_single_"+customerEdit.id).hide();
   
        $("#team_"+customerEdit.id).bind('change',function(){
                    customerEdit.team=$("#team_"+customerEdit.id).val();
               });
        $("#category_"+customerEdit.id).bind('change',function(){
            customerEdit.category=$("#category_"+customerEdit.id).val();
       });
        self_.operationType = 3; 
   }
   
    self_.changeCustomerGroup=function(customerEdit)
    {
         $("#tr_"+customerEdit.id).show();
         $("#submit_button_"+customerEdit.id).show(); 
        //单人组
        if(customerEdit.group=='单人组')
        {
            $("#form_single_"+customerEdit.id).show();
            $("#form_double_"+customerEdit.id).hide();
            $("#form_edit_"+customerEdit.id).hide();
            $("#group_members_"+customerEdit.id).find("option").remove();
            $("#group_members_"+customerEdit.id).append("<option value='0'>请选择组员</option>");
            ycoa.ajaxLoadGet("/api/second_sale_soft/customer_edit.php?action=2&userid="+customerEdit.userid+"&team="+customerEdit.team,null,function (results) {
             $.each(results.list, function(index, customerEditForChangeGroup) {
                
             $("#group_members_"+customerEdit.id).append("<option value='"+customerEditForChangeGroup.userid+"'>"+customerEditForChangeGroup.username+"</option>");
            });
              });
            //下拉选择了值后将值传入到customerEdit
            $("#group_members_"+customerEdit.id).bind('change',function(){
                 customerEdit.group_members = $("#group_members_"+customerEdit.id).val();
            });
            $("#team_"+customerEdit.id).bind('change',function(){
                 customerEdit.team = $("#team_"+customerEdit.id).val();
            });
             $("#category_"+customerEdit.id).bind('change',function(){
                 customerEdit.category = $("#category_"+customerEdit.id).val();
            });
              self_.operationType = 1;
        }
        else
        {
              $("#form_double_"+customerEdit.id).show();
              $("#form_single_"+customerEdit.id).hide();
              $("#form_edit_"+customerEdit.id).hide();
              //customerEdit中的team和category会跟随着team_stuff，category_stuff变化，实在是奇怪。工期紧先用其他办法绕过，打个记号先。
                customerEdit.category_stuff = $("#category_stuff_"+customerEdit.id).val();
                customerEdit.team_stuff = $("#team_stuff_"+customerEdit.id).val();
                customerEdit.category_owner = $("#category_owner_"+customerEdit.id).val();
                customerEdit.team_owner = $("#team_owner_"+customerEdit.id).val();
                customerEdit.team = undefined;
                customerEdit.category = undefined;
             
               //下拉选择了值后将值传入到customerEdit
               $("#category_owner_"+customerEdit.id).bind('change',function(){
                     customerEdit.category_owner = $("#category_owner_"+customerEdit.id).val();
              });
              $("#team_owner_"+customerEdit.id).bind('change',function(){
                     customerEdit.team_owner = $("#team_owner_"+customerEdit.id).val();
              });
               $("#category_stuff_"+customerEdit.id).bind('change',function(){
                     customerEdit.category_stuff = $("#category_stuff_"+customerEdit.id).val();
              });
              $("#team_stuff_"+customerEdit.id).bind('change',function(){
                     customerEdit.team_stuff = $("#team_stuff_"+customerEdit.id).val();
              });
              self_.operationType = 2;
        }
    };
    
    self_.doSubmitOperateCustomer = function (customerEdit) {
 
        switch(self_.operationType)
        {
            case 1:
            default:
               customerEdit.action = 1;
               customerEdit.group = "双人组";
              
               //验证转Q数输入
                if($("#group_members_"+customerEdit.id).val() == 0)
                {
                     ycoa.UI.toast.warning("<div style='color:red;font-size:18px;'>请选择组员</div>~");
                     exit;
                }
               break;
           case 2:
                customerEdit.action = 2;
                customerEdit.group = "单人组";
              break;
          case 3:
               customerEdit.action=3;
               break;    
        }
            ycoa.ajaxLoadPost("/api/second_sale_soft/customer_edit.php", JSON.stringify(customerEdit), function (result) {
 
                if (result.code == 0) {
                    ycoa.UI.toast.success(result.msg);
                    reLoadData({action: 1});
                } else {
                    ycoa.UI.toast.error(result.msg);
                }
                ycoa.UI.block.hide();
            });
        }
    }();
   
$(function () {
    ko.applyBindings(CustomerEditViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").reLoad(function () {
    reLoadData({action: 1});
    $('#searchUserName').val("");});
    
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(),searchName: name});
    }, '按名称查找');
});

function reLoadData(data) {
    data.action=1;
    CustomerEditViewModel.listCustomerEdit(data);
}
