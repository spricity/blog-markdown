<?php
include_once(BASEDIR . '/view/mods/header.php');
?>
<script type="text/javascript" src="<?=BASEURL?>view/assets/zepto.min.js"></script>
<script type="text/javascript" src="<?=BASEURL?>view/assets/knockout-3.1.0.js"></script>
<main class="pull-margin-3 pull-mt-40 tree" style="display:none;">
    <!-- ko foreach: tree -->
    <div class="tree-wrap">
        <div class="index" data-bind="text: $index() + 1"></div>
        <div class="tree-line-wrap">
            <div class="input markdown-edit-title tree-title tree-line-flex">
                <label>
                    <span>名称:</span>
                    <input type="text" name="title" class="txt" id="title" data-bind="value:name">
                </label>
            </div>

<!--             <div class="input markdown-edit-title tree-title tree-line-flex">
                <label>
                    <span>path:</span>
                    <input type="text" name="title" class="txt" id="title" data-bind="value:path">
                </label>
            </div> -->
            <div class="input markdown-edit-title tree-title tree-line-flex">
                <label>
                    <span>固定URL:</span>
                    <input type="text" name="title" class="txt" id="title" data-bind="value:url">
                </label>
            </div>
            <div class="input markdown-edit-title tree-title">
                <label>
                    <span>排序:</span>
                    <input type="text" name="title" class="txt" id="title" data-bind="value:sort">
                </label>
            </div>
            <div class="controls removekey">
                <label><a href="javascript:;" data-bind="click: $root.removeTree" class="keybtn">- 删除</a></label>
            </div>
        </div>
        <div class="h-block">
            <div class="h-item">
                <div class="control-group keyTitle">
                    <!-- ko foreach: { data: child } -->
                    <div class="input-type-wrap tree-line-wrap">
                        <div class="input markdown-edit-title tree-title tree-line-flex">
                            <label>
                                <span>名称:</span>
                                <input type="text" name="title" class="txt" id="title" data-bind="value:name">
                            </label>
                        </div>
<!--                         <div class="input markdown-edit-title tree-title tree-line-flex">
                            <label>
                                <span>路径:</span>
                                <input type="text" name="title" class="txt" id="title" data-bind="value:path">
                            </label>
                        </div> -->
                        <div class="input markdown-edit-title tree-title tree-line-flex">
                            <label>
                                <span>固定URL:</span>
                                <input type="text" name="title" class="txt" id="title" data-bind="value:url">
                            </label>
                        </div>
                        <div class="controls removekey">
                            <label><a href="javascript:;" data-bind="click: removeChild" class="keybtn">- 删除二级目录</a></label>
                        </div>

                    </div>
                    <!-- /ko -->
                    <div class="input-type add-child">
                        <button type="text" placeholder="单击可添加选项" autocomplete="off" data-bind="click: addChild" class="input-option-add">
                            单击可添加二级目录
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- /ko -->
        <a href="javascript:;" data-bind="click: $root.addTree" class="button button-green">+ 添加一级目录</a>
        <a href="javascript:;" data-bind="click: $root.save" class="button button-red">保存</a>
</main>

<script>
;(function(){
    $.get('<?=BASEURL?>?a=edit_tree&category=<?=$category?>', {}, function(j){
        if(j.code == 200){
            var init = function(view){
                if(j.msg.length > 0){
                    $.each(j.msg, function(i, item){
                        view.tree.push(new SingleTree(item));
                    });
                }else{
                    view.tree.push(new SingleTree);
                }
            }

            var Child = function(parent, item){
                item = item || {};
                this.name = ko.observable(item.name || '');
                this.url = ko.observable(item.url || '');
                this.path = ko.observable(item.path || '');
                this.removeChild = function(){
                    parent.child.remove(this);
                }
            }

            var SingleTree = function(item){
                var self = this;
                item = item || {};
                this.name = ko.observable(item.name || '');
                this.url = ko.observable(item.url || '');
                this.path = ko.observable(item.path || '');
                this.sort = ko.observable(item.sort || 1);
                if(item.child){
                    // console.log(item.)
                    this.child = ko.observableArray();
                    $.each(item.child, function(i, child){
                        self.child.push(new Child(self, child));
                    })
                }else{
                    this.child = ko.observableArray([new Child(this)]);
                }
                this.addChild = function(){
                    this.child.push(new Child(this, {}));
                }
            }
            $(".tree").show();
            var ViewModel = function(){
                var self = this;
                this.tree = ko.observableArray();
                this.addTree = function(){
                    self.tree.push(new SingleTree(self));
                }
                this.removeTree = function(){
                    self.tree.remove(this);
                }
                this.save = function(){
                    var js = ko.toJSON(self);
                    $.post('<?=BASEURL?>?a=edit_tree&category=<?=$category?>&c=save', {content: js}, function(j){
                        if(j.code == 200){
                            alert('编辑成功!');
                        }
                    });
                }
            }
            var view = new ViewModel;
            ko.applyBindings(view);                                 // 绑定 KO
            init(view);

        }
    });
})();
</script>
<?include_once(BASEDIR . '/view/mods/footer.php')?>
