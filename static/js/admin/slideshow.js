/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */
define([
    'jquery-nos'
], function($) {
    return function(appDesk) {
        return {
            tab : {
                label : appDesk.i18n('slideshows'),
                iconUrl : 'static/apps/slideshow/img/slideshow-32.png'
            },
            actions : {
                update : {
                    action : function(item, ui) {
                        $(ui).nosTabs({
                            url     : "admin/slideshow/form/edit/" + item.id,
                            label   : appDesk.i18n('Edit')._()
                        });
                    },
                    label : appDesk.i18n('Edit'),
                    name : 'edit',
                    primary : true,
                    icon : 'pencil'
                },
                'delete' : {
                    action : function(item, ui) {
                        $(ui).nosConfirmationDialog({
                            content     : appDesk.i18n('Delete this slideshow ?')._(),
                            title       : appDesk.i18n('Delete a slideshow')._(),
                            confirmed   : function() {
                                $(ui).xhr({
                                    url : 'admin/slideshow/slideshow/delete_confirm/'+item.id,
                                    method : 'GET',
                                    success : function(json) {
                                        $(ui).nosDialog('close');
                                        $.nosDispatchEvent('reload.slideshow');
                                    }
                                });
                            }
                        });
                    },
                    label : appDesk.i18n('Delete'),
                    name : 'delete',
                    primary : true,
                    icon : 'trash'
                },
                'visualise' : {
                    label : 'Visualise',
                    name : 'visualise',
                    primary : true,
                    iconClasses : 'nos-icon16 nos-icon16-eye',
                    action : function(item, ui) {
                        $(ui).nosTabs({
                            url     : "admin/slideshow/form/edit/" + item.id,
                            label   : appDesk.i18n('Edit')._()
                        });
                        //window.open(item.url + '?_preview=1');
                    }
                }
            },
            reloadEvent : 'slideshow',
            appdesk : {
                adds : {
                    post : {
                        label : appDesk.i18n('Add a slideshow'),
                        action : function(ui, appdesk) {
                            $(ui).nosTabs('add', {
                                url     : 'admin/slideshow/form/add?lang=' + appdesk.lang,
                                label   : appDesk.i18n('Add a new post')._()
                            });
                        }
                    }
                },
                splittersVertical :  250,
                grid : {
                    proxyUrl : 'admin/slideshow/appdesk/json',
                    columns : {
                        title : {
                            headerText : appDesk.i18n('Title'),
                            dataKey : 'title'
                        },
                        actions : {
                            actions : ['update', 'delete', 'visualise']
                        }
                    }
                },
                inspectors : {
                }
            }
        };
    };
});
