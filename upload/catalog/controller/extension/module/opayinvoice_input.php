<?php
?>
    <script type="text/javascript">
        // 清除發票資訊
        function remove_invoice_info() {
            $.ajax({
                type: 'POST',
                async : false,
                url: 'index.php?route=extension/payment/opayinvoice/del_invoice_info',
                dataType: 'html',
                data: 'invoice_status=0',
                success: function (sMsg){
                    // alert(sMsg);
                },
                error: function (sMsg1, sMsg2){
                    // alert("失敗");
                }
            });
        }
    </script>

<?php
// 判斷電子發票是否啟動
if( $opayinvoice_enabled )
{
    ?>

    <strong>發票開立</strong>
    <br>
    <input type="radio" name="invoice_type" id="invoice_type_1" value="1" checked="checked"> 個人發票&nbsp;&nbsp;
    <input type="radio" name="invoice_type" id="invoice_type_2" value="2"> 公司戶發票&nbsp;&nbsp;
    <input type="radio" name="invoice_type" id="invoice_type_3" value="3"> 捐贈&nbsp;&nbsp;
    <input type="radio" name="invoice_type" id="invoice_type_4" value="4"> 歐付寶載具

    <div class="invoice_info"  style="display:none;" >
        <input type="text" name="company_write" id="company_write" value="" placeholder="統一編號" class="form-control">
    </div>

    <div class="donation_info" style="display:none;" >
        <input type="text" name="love_code" id="love_code" value="" placeholder="請輸入愛心碼3-7位數" class="form-control">
        <a href="https://www.einvoice.nat.gov.tw/APMEMBERVAN/XcaOrgPreserveCodeQuery/XcaOrgPreserveCodeQuery" target="_blank">
            愛心碼查詢
        </a>
    </div>

    <hr>

    <script type="text/javascript">

        // 記錄發票資訊
        function save_invoice_info() {
            var invoice_type = $('input:radio[name="invoice_type"]:checked').val();
            var company_write 	= $("#company_write").val()
            var love_code 		= $("#love_code").val()

            $.ajax({
                type: 'POST',
                async : false,
                url: 'index.php?route=extension/payment/opayinvoice/set_invoice_info',
                dataType: 'html',
                data: 'company_write='+company_write+'&love_code='+love_code+'&invoice_type='+invoice_type+'&invoice_status=1',
                success: function (sMsg){
                    // alert(sMsg);
                },
                error: function (sMsg1, sMsg2){
                    // alert("失敗");
                }
            });
        }

        // 驗證發票資訊
        function validate_invoice_info() {
            var invoice_type = $('input:radio[name="invoice_type"]:checked').val();
            if(invoice_type == null) {
                return "請選擇發票開立類型";
            } else {
                // 統一編號檢查
                if (invoice_type == 2) {
                    var company_write = $('input:text[name="company_write"]').val();
                    if (company_write === '') {
                        return "請填寫統一編號";
                    }

                    var result = company_write.match(/^\d{8}$/);
                    if(result == null) {
                        return "統一編號格式錯誤";
                    }
                }

                // 愛心碼檢查
                if (invoice_type == 3) {
                    var love_code = $('input:text[name="love_code"]').val();
                    if (love_code === '') {
                        return "請填寫愛心碼";
                    }

                    var result = love_code.match(/^([xX]{1}[0-9]{2,6}|[0-9]{3,7})$/);
                    if(result == null) {
                        return "愛心碼格式錯誤";
                    }
                }
            }
            return "";
        }

        // 個人發票
        $( "#invoice_type_1" ).on( "click", function() {
            $(".invoice_info").slideUp();
            $(".donation_info").slideUp();

            $("#company_write").val("");
            $("#love_code").val("");
        });

        // 公司發票
        $( "#invoice_type_2" ).on( "click", function() {
            $(".invoice_info").slideDown();
            $(".donation_info").slideUp();

            $("#love_code").val("");
        });

        // 捐贈
        $( "#invoice_type_3" ).on( "click", function() {
            $(".invoice_info").slideUp();
            $(".donation_info").slideDown();

            $("#company_write").val("");
        });

        // 歐付寶載具
        $( "#invoice_type_4" ).on( "click", function() {
            $(".invoice_info").slideUp();
            $(".donation_info").slideUp();

            $("#company_write").val("");
            $("#love_code").val("");
        });

        // 送出按鈕，寫入SESSION
        $("#button-payment-method").click(function(){
            var result = validate_invoice_info();
            if (result !== "") {
                alert(result);
                return false;
            } else {
                // 清除發票資訊
                remove_invoice_info();

                // 記錄發票資訊
                save_invoice_info();
            }
        });
    </script>
    <?php
} else {
    ?>

    <script type="text/javascript">
        $( document ).ready(function() {
            remove_invoice_info;
        });
    </script>

<?php
}
?>