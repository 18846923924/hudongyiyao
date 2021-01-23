/**
 * Created by PhpStorm.
 *
 * Date: 2019/4/25
 */


//父元素要设置为 postion:relative;

function Modal(text = '',type = 1 ,node = 'body'){
    this.text = text;
    this.node = node;
    this.type = type;
}

Modal.prototype.show = function(){
    let className = 'gao_modal_mask_index_' + new Date().getTime()
    let html = `
		<div class="gao_modal_mask_index" style = 'display:none;'>
			<div class="gao_modal_mask">
				<img src="/public/static/img/loading1.gif" class="gao_modal_loading">
				${this.text == '' ?   '' : '<p class="gao_modal_loading_text">' + this.text+ '</p>'}
			</div>
		</div>
	`;
    $(this.node).append(html)
    $(`.gao_modal_mask_index`).fadeIn(200)

    return 'gao_modal_mask_index'
};

Modal.prototype.close = function(){
    console.log('a.close')
};



let Loading = $.extend({
    showModal:function(text,type,node,remove=false){
        if(remove){
            $("[class='gao_modal_mask_index']").remove(); // 创建新的loading之前 先移除现有的loading层
        }

        let a = new Modal(text,type,node);
        return a.show()
    },
    closeModal:function(className=''){
        if(className){
            $('.'+className).remove()
        }else{
            $("[class='gao_modal_mask_index']").fadeOut(300).remove()
        }

    }
})
