;(function($){
    /**
     * All action for tree
     * @type object
     */
    var trees = {
        /**
         * tree options
         */
        options:{
            b:'#tree_wrap',
            p:'.tree',
            url: location.protocol + '//' + location.hostname  + ( location.port ? ':' + location.port : '' ) + '/',
            t: '.toggle-branch',
            a: '#add_node',
            d: '#del_node',
            c: 'childrens',
            ac: 'active',
            da: 'drop-active',
            dp: 'ui-state-highlight',
            icd: 'fa-times',
            ict: 'fa-caret-up',
            ica: 'fa-check-square',
            oldname:''
        },
        /**
         * tree init 
         */
        init: function(){
            this.bind();
            this.load_tree();
        },
        /**
         * bind user actions
         */
        bind: function(){
            var _t=this,o=_t.options;
            //add node
            $(o.a).on('click', function(){
                _t.node_add();
            });
            //select node
            $(o.b).on('click', 'li', function(e){
                e.stopImmediatePropagation();
                _t.node_active($(this));
            });
            $(o.b).on('click', 'a', function(e){
                e.stopImmediatePropagation();
                _t.node_active($(this).closest('li'));
            });
            //click any place - except node - unselect all
            $(window).on('click', function(){
                $(o.b).find('li').removeClass(o.ac);
                $(o.b).find(o.d).remove();
            });
            //need rename node
            $(o.b).on('dblclick', 'li', function(e){
                e.stopImmediatePropagation();
                _t.node_rename($(this).children('a'));
            });
            $(o.b).on('dblclick', 'a', function(e){
                e.stopImmediatePropagation();
                _t.node_rename($(this));
            });
            //finish rename node - save
            $(o.b).on('keyup', 'input', function(e){
                if(e.keyCode === 13 || e.which === 13){
                    $(this).trigger('blur');
                }
            });
            $(o.b).on( "blur", 'input', function(e){
                e.stopImmediatePropagation();
                _t.node_change_name($(this));
            });
            $(o.b).on('click', '.' + o.ica , function(){
                $(this).siblings('input').trigger('blur');
            });
            //delete node - with all children
            $(o.b).on('click', o.d, function(){
                if(confirm('This will delete node with all children!\nContinue ?')){
                    _t.node_delete($(this).closest('li'));
                }
            });
            //toggle branch
            $(o.b).on('click', o.t, function(e){
                e.stopImmediatePropagation();
                $(this).toggleClass('closed');
                $(this).closest('li').children(o.p).fadeToggle();
            });
        },
        /**
         * get tree data
         */
        load_tree: function(){
            var _t=this,o=_t.options, action;
            action = $.ajax({
                'url': o.url,
                'type': 'post',
                'dataType': 'json',
                'data': {'action':'list'}
            });
            action
                .done(function(json){
                    _t.show_tree(json['nodes']);
                })
                .fail(function(jqXHR, textStatus){
                    console.error(textStatus);
                });
        },
        /**
         * show nodes tree, init drag&drop
         * @param array data
         */
        show_tree: function(data){
            var _t=this,o=_t.options, h='';
            if(data.length){
                h += _t.show_node(data);
            }
            $(o.b).html('<ul class="'+ o.p.substring(1) +'">' + h + '</ul>');
            _t.check_tree();
            _t.node_drag();
            _t.node_drop();
        },
        /**
         * show one node - recursive show childrens
         * @param array nodes
         * @returns string
         */
        show_node: function(nodes){
            var _t=this,o=_t.options,cl='', h='';
            $.each(nodes, function(i, node){
                cl='';
                if(typeof(node['childrens']) !== 'undefined' && node['childrens'].length){
                    cl = _t.show_node(node['childrens']);
                }
                h += '<li data-id="' + node['id'] + '" data-parent-id="' + node['parent_id'] + '" data-order="' + node['order'] + '">'
                    + '<a draggable="false">' + node['name'] + '</a>'
                    + '<ul class="'+ o.p.substring(1) +'">' + cl + '</ul>'
                    + '</li>';
            });
            return h;
        },
        /**
         * check childrens item
         */
        check_tree: function(){
            var _t=this,o=_t.options, c;
            $(o.b).find('li').each(function(){
                c = $(this).children(o.p).children('li').length;
                $(this).removeClass(o.c);
                $(this).children(o.t).remove(); 
                if(c){
                    $(this).addClass(o.c);
                    $(this).prepend('<span class="'+ o.t.substring(1) +'"><i class="fa '+ o.ict +'"></i></span>');
                }
            });
        },
        /**
         * save branch items order
         * @param int id
         */
        branch_order: function(id){
            var _t=this,o=_t.options,p,c, order={};
            if(id !== 0){
                p=$('li[data-id="'+ id +'"]'); 
            } else {
                p = $(o.b);
            }
            c=p.children(o.p).children('li');
            if(c.length){
                c.each(function(i){
                    order[$(this).data('id')] = i;
                    $(this).data('order', i);
                });
                $.ajax({
                    'url': o.url,
                    'type': 'post',
                    'dataType': 'json',
                    'data': {action: 'order', value: order }
                });
            }
        },
        /**
         * drag functional - jquery ui sortable
         */
        node_drag: function(){
            var _t=this,o=_t.options, old_branch=0, new_branch, p;
            $(o.b).find(o.p).sortable({ 
                revert: 200, 
                cursor: "move", 
                placeholder: o.dp,
                connectWith: o.p,
                tolerance: "pointer",
                start: function(){
                    //open closed branch
                    $(o.b).find(o.t+'.closed').each(function(){
                        $(this).closest('li').children(o.p).fadeIn();
                    });
                },
                stop: function(e,ui){
                    _t.check_tree();
                    p=ui.item.closest(o.p).closest('li');
                    old_branch = ui.item.data('parent-id');
                    new_branch=0;
                    if(p.length){
                        new_branch = p.data('id');
                    }
                    ui.item.data('parent-id', new_branch);
                    if(new_branch !== old_branch){
                        $.ajax({
                            'url': o.url,
                            'type': 'post',
                            'dataType': 'json',
                            'data': {action: 'update', type:'parent_id', value: new_branch, id: ui.item.data('id') }
                        });
                        _t.branch_order(old_branch);
                    }
                    _t.branch_order(new_branch);
                }
            });
        },
        /**
         * drop functional - jquery ui droppable
         */
        node_drop: function(){
            var _t=this,o=_t.options;
            $(o.b).find('li').each(function(){
                $(this).droppable({
                    activate: function() {
                        $(this).addClass(o.da);
                        $(this).children("");
                    },
                    deactivate: function() {
                        $(this).removeClass(o.da);
                    }
                });
            });
        },
        /**
         * set active node
         * @param object elm
         */
        node_active: function(elm){
            var _t=this,o=_t.options;
            $(o.b).find('li').removeClass(o.ac);
            $(o.b).find(o.d).remove();
            elm.addClass(o.ac);
            elm.append('<span id="'+ o.d.substring(1) +'"><i class="fa '+ o.icd +'"></i></span>');
        },
        /**
         * delete node
         * @param object elm
         */
        node_delete: function(elm){
            var _t=this,o=_t.options,p=elm.closest('ul').closest('li'),action;
            action = $.ajax({
                'url': o.url,
                'type': 'post',
                'dataType': 'json',
                'data': {action:'destroy', id: elm.data('id')}
            });
            action
                .done(function(json){
                    if(json['status']){
                        elm.remove();
                    } else {
                        console.error(json['message']);
                    }
                })
                .fail(function(jqXHR, textStatus){
                    console.error(textStatus);
                });
            _t.branch_order(p.data('id'));
        },
        /**
         * show input for add new node
         */
        node_add: function(){
            var _t=this,o=_t.options, p, n;
            p = $(o.b).find('.' + o.ac);
            if(p.length){ //add children node
                n = '<li data-id="0" data-parent-id="'+ p.data('id') +'" data-order="' + (p.children('ul').children('li').length + 1) + '">'
                   +'<input type="text" value=""><i class="fa '+ o.ica +'"></i>'
                   +'<ul class="'+ o.p.substring(1) +'"></ul>'
                   +'</li>';
               p.children('ul').append(n);
            } else {    //add root node
                p = $(o.b).children(o.p);
                if(! p.length){
                    $(o.b).append('<ul class="'+ o.p.substring(1) +'"></ul>');
                    p = $(o.b).children(o.p);
                }
                n = '<li data-id="0" data-parent-id="0" data-order="' + (p.children('li').length + 1) + '">'
                   +'<input type="text" value=""><i class="fa '+ o.ica +'"></i>'
                   +'<ul class="'+ o.p.substring(1) +'"></ul>'
                   +'</li>';
                p.append(n);
            }
            p.find('input').focus();
        },
        /**
         * show input for rename node
         * @param object elm
         */
        node_rename: function(elm){
            var _t=this,o=_t.options,t = elm.text(), p = elm.closest('li');
            o.oldname = t;
            elm.replaceWith('<input type="text" value="'+ t +'" /><i class="fa '+ o.ica +'"></i>');
            p.find('input').focus();
        },
        /**
         * change node name + add new node
         * @param object elm
         */
        node_change_name: function(elm){
            var _t=this,o=_t.options,name = elm.val(), p = elm.closest('li'),action;
            if(p.data('id') === 0){
                action_name = 'store';
            } else {
                action_name = 'update';
            }
            //if try rename...
            if(action_name === 'update'){
                //...and get empty node name - restore old node name 
                if(typeof(name)==='undefined' || name === ""){
                    elm.siblings('.' + o.ica ).remove();
                    elm.replaceWith('<a draggable="false">' + o.oldname + '</a>');
                    console.error('Empty node name !');
                    return;
                }
                //... and get same name as old name - restore old node name 
                if(name === o.oldname ){
                    elm.siblings('.' + o.ica ).remove();
                    elm.replaceWith('<a draggable="false">' + o.oldname + '</a>');
                    console.info('Same node name !');
                    return;
                }
            }
            //if try add new node - and get empty node name - destroy block
            if(action_name === 'store' && (typeof(name)==='undefined' || name === "")){
                elm.closest('li').remove();
                console.error('Empty node name !');
                return;
            }
            action = $.ajax({
                'url': o.url,
                'type': 'post',
                'dataType': 'json',
                'data': {action: action_name, type:'name', value: name, id: p.data('id'), parent: p.data('parent-id'), order: p.data('order') }
            });
            action
                .done(function(json){
                    if(json['status'] === true){
                        if(action_name === 'store'){
                            p.data('id', json['message']);
                            _t.node_drag();
                            _t.node_drop();
                        } 
                        elm.siblings('.' + o.ica ).remove();
                        elm.replaceWith('<a draggable="false">' + name + '</a>');
                    } else {
                        console.error(json['message']);
                    }
                })
                .fail(function(jqXHR, textStatus){
                    console.error(textStatus);
                });
        }
    };
    
    /**
     * run trees init - on document ready
     */
    $(document).ready(function(){
        trees.init();
    });
})(jQuery);