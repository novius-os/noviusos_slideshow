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
], function($nos) {
    return function(appDesk) {
        return {
            tab : {
                label : appDesk.i18n('Diaporamas'),
                iconUrl : 'static/apps/diaporama/img/diaporama-32.png'
            },
            actions : {
                update : {
                    action : function(item, ui) {
                        $nos(ui).nosTabs({
                            url     : "admin/diaporama/form/edit/" + item.id,
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
                        $nos(ui).confirmationDialog({
                            content     : appDesk.i18n('Delete this diaporama ?')._(),
                            title       : appDesk.i18n('Delete a diaporama')._(),
                            confirmed   : function() {
                                $nos(ui).xhr({
                                    url : 'admin/diaporama/diaporama/delete_confirm/'+item.id,
                                    method : 'GET',
                                    success : function(json) {
                                        $nos(ui).dialog('close');
                                        $nos.dispatchEvent('reload.diaporama');
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
                    action : function(item) {
                        window.open(item.url + '?_preview=1');
                    }
                }
            },
            reloadEvent : 'diaporama',
            appdesk : {
                adds : {
                    post : {
                        label : appDesk.i18n('Add a slideshow'),
                        action : function(ui, appdesk) {
                            $nos(ui).nosTabs('add', {
                                url     : 'admin/diaporama/form/add?lang=' + appdesk.lang,
                                label   : appDesk.i18n('Add a new post')._()
                            });
                        }
                    }
                },
                splittersVertical :  250,
                grid : {
                    proxyUrl : 'admin/diaporama/index/json',
                    columns : {
                        nom : {
                            headerText : appDesk.i18n('Nom'),
                            dataKey : 'nom'
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
