function buttons() {
    return {
        updateAll: null
    }
}

// 店铺状态
function storeStatus(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonStoreStatus, config.data.jsonStoreStatusColor, data,'status'));
}

// 店铺二级域名状态
function storeDomainStatus(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonStoreDomainStatus, config.data.jsonStoreDomainStatusColor, data,'is_open_store'));
}

// 店铺logo
function storeLogo(td,data) {
    $(td).html('<img src="'+data+'" width="100" />');
}

// 身份证正面
function storeUserIDCardFront(td,data) {
    $(td).html('<img src="'+data+'" width="100" />');
}

// 身份证反面
function storeUserIDCardSide(td,data) {
    $(td).html('<img src="'+data+'" width="100" />');
}

// 获取城市
function getChinaCity(cityId,selectedId,type){
    ajax(config.url.getChinaCityUrl,{cityId:cityId,selectedId:selectedId},function(res){
        if (type == 'city'){
            $('select[name="city"]').html(res.options);
            $('select[name="district"]').html('<option value="0">请选择区</option>');
        } else {
            $('select[name="district"]').html(res.options);
        }
    })
}

// 获取省份
function _getChinaProvince () {
    var cityId = $(this).val();
    ajax(config.url.getChinaCityUrl,{cityId:cityId},function(res){
        $('select[name="city"]').html(res.options);
        $('select[name="district"]').html('<option value="0">请选择区</option>');
    })
}

// 获取区域
function _getChinaDistrict() {
    var cityId = $(this).val();
    ajax(config.url.getChinaCityUrl,{cityId:cityId},function(res){
        $('select[name="district"]').html(res.options);
    })
}