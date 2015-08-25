!function(t,o){"use strict";"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(a,r){return o(t,a,r)}):"object"==typeof exports?module.exports=o(t,require("wolfy87-eventemitter"),require("eventie")):t.imagesLoaded=o(t,t.EventEmitter,t.eventie)}(window,function(t,o,a){"use strict";function r(t,o){for(var a in o)t[a]=o[a];return t}function e(t){return"[object Array]"===p.call(t)}function i(t){var o=[];if(e(t))o=t;else if("number"==typeof t.length)for(var a=0,r=t.length;r>a;a++)o.push(t[a]);else o.push(t);return o}function n(t,o,a){if(!(this instanceof n))return new n(t,o);"string"==typeof t&&(t=document.querySelectorAll(t)),this.elements=i(t),this.options=r({},this.options),"function"==typeof o?a=o:r(this.options,o),a&&this.on("always",a),this.getImages(),u&&(this.jqDeferred=new u.Deferred);var e=this;setTimeout(function(){e.check()})}function s(t){this.img=t}function c(t){this.src=t,m[t]=this}var u=t.jQuery,l=t.console,d="undefined"!=typeof l,p=Object.prototype.toString;n.prototype=new o,n.prototype.options={},n.prototype.getImages=function(){this.images=[];for(var t=0,o=this.elements.length;o>t;t++){var a=this.elements[t];"IMG"===a.nodeName&&this.addImage(a);var r=a.nodeType;if(r&&(1===r||9===r||11===r))for(var e=a.querySelectorAll("img"),i=0,n=e.length;n>i;i++){var s=e[i];this.addImage(s)}}},n.prototype.addImage=function(t){var o=new s(t);this.images.push(o)},n.prototype.check=function(){function t(t,e){return o.options.debug&&d&&l.log("confirm",t,e),o.progress(t),a++,a===r&&o.complete(),!0}var o=this,a=0,r=this.images.length;if(this.hasAnyBroken=!1,!r)return void this.complete();for(var e=0;r>e;e++){var i=this.images[e];i.on("confirm",t),i.check()}},n.prototype.progress=function(t){this.hasAnyBroken=this.hasAnyBroken||!t.isLoaded;var o=this;setTimeout(function(){o.emit("progress",o,t),o.jqDeferred&&o.jqDeferred.notify&&o.jqDeferred.notify(o,t)})},n.prototype.complete=function(){var t=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var o=this;setTimeout(function(){if(o.emit(t,o),o.emit("always",o),o.jqDeferred){var a=o.hasAnyBroken?"reject":"resolve";o.jqDeferred[a](o)}})},u&&(u.fn.imagesLoaded=function(t,o){var a=new n(this,t,o);return a.jqDeferred.promise(u(this))}),s.prototype=new o,s.prototype.check=function(){var t=m[this.img.src]||new c(this.img.src);if(t.isConfirmed)return void this.confirm(t.isLoaded,"cached was confirmed");if(this.img.complete&&void 0!==this.img.naturalWidth)return void this.confirm(0!==this.img.naturalWidth,"naturalWidth");var o=this;t.on("confirm",function(t,a){return o.confirm(t.isLoaded,a),!0}),t.check()},s.prototype.confirm=function(t,o){this.isLoaded=t,this.emit("confirm",this,o)};var m={};return c.prototype=new o,c.prototype.check=function(){if(!this.isChecked){var t=new Image;a.bind(t,"load",this),a.bind(t,"error",this),t.src=this.src,this.isChecked=!0}},c.prototype.handleEvent=function(t){var o="on"+t.type;this[o]&&this[o](t)},c.prototype.onload=function(t){this.confirm(!0,"onload"),this.unbindProxyEvents(t)},c.prototype.onerror=function(t){this.confirm(!1,"onerror"),this.unbindProxyEvents(t)},c.prototype.confirm=function(t,o){this.isConfirmed=!0,this.isLoaded=t,this.emit("confirm",this,o)},c.prototype.unbindProxyEvents=function(t){a.unbind(t.target,"load",this),a.unbind(t.target,"error",this)},n});var SocialCurator=SocialCurator||{};SocialCurator.Formatter=function(){var t=this,o=jQuery;t.selectors={unmoderatedCount:"[data-social-curator-unmoderated-count]"},t.subtractUnmoderated=function(){var a=parseInt(o(t.selectors.unmoderatedCount).first().text());a-=1,o(t.selectors.unmoderatedCount).text(a)},t.addUnmoderated=function(){var t=parseInt(o("[data-social-curator-unmoderated-count]").first().text());t+=1,o("[data-social-curator-unmoderated-count]").text(t)},t.displayApproval=function(t,a){var r='<div class="social-curator-alert-success">'+SocialCurator.localizedText.approvedBy+" "+t.approved_by+" "+SocialCurator.localizedText.on+" "+t.approved_date;"1"===social_curator_admin.can_delete_posts&&(r+='<br><a href="#" data-trash-post data-post-id="'+t.id+'" class="unapprove-link">Unapprove and Trash</a>'),r+="</div>";var e=a?a:o("[data-post-container-id="+t.id+"]");o(e).find(".social-curator-status-buttons").remove(),o(e).append(r),o(e).addClass("approved")},t.displayTrashedButtons=function(t,a){var r='<div class="social-curator-status-buttons">';r+='<button data-permanent-delete-post data-post-id="'+t.id+'" class="social-curator-trash"><i class="social-curator-icon-blocked"></i>'+social_curator_admin.permanently_delete+"</button>",r+='<button data-restore-post data-post-id="'+t.id+'" class="social-curator-approve"><i class="social-curator-icon-redo"></i>'+social_curator_admin.restore+"</button>";var e=a?a:o("[data-post-container-id="+t.id+"]");o(e).find(".social-curator-status-buttons").remove(),o(e).append(r)}};var SocialCurator=SocialCurator||{};SocialCurator.BulkImport=function(){var t=this,o=jQuery;return t.post_ids="[data-bulk-import-ids]",t.site="[data-bulk-import-site]",t.button=o("[data-bulk-import]"),t.statusAlert=o("[data-import-status]"),t.errorAlert=o("[data-import-error]"),t.successAlert=o("[data-import-success]"),t.init=function(){t.bindEvents()},t.bindEvents=function(){o(t.button).on("click",function(o){o.preventDefault(),t.startImport()})},t.startImport=function(){t.loading(!0),o.ajax({url:ajaxurl,type:"POST",data:{nonce:social_curator_admin.social_curator_nonce,action:"social_curator_bulk_import",site:o(t.site).val(),post_ids:o(t.post_ids).val()},success:function(a){return t.loading(!1),"error"===a.status?void o(t.errorAlert).text(a.message).show():void t.showSuccess(a)}})},t.showSuccess=function(a){if(html="<p><strong>"+a.import_count+" new posts imported.</strong></p>",a.errors.length>0){html+="<p>There were "+a.errors.length+" errors during the import:</p><ul>";for(var r=0;r<a.errors.length;r++)html+="<li>"+a.errors[r]+"</li>";html+="</ul>"}o(t.successAlert).html(html).show()},t.loading=function(a){return a?(o(t.statusAlert).show(),o(t.errorAlert).hide(),o(t.successAlert).hide(),o(t.button).text(social_curator_admin.importing).attr("disabled","disabled"),void o("[data-bulk-import-loader]").show()):(o(t.statusAlert).hide(),o(t.button).text(social_curator_admin.run_import).attr("disabled",!1),void o("[data-bulk-import-loader").hide())},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Dropdowns=function(){var t=this,o=jQuery;return t.toggleButton='[data-toggle="social-curator-dropdown"]',t.currentDropdown="",t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.toggleButton,function(a){a.preventDefault(),t.currentDropdown=o(this).siblings(".social-curator-dropdown-content"),t.toggleDropdown()}),o(document).on("click",function(o){t.windowListener(o.target)})},t.toggleDropdown=function(){o(t.currentDropdown).is(":visible")?o(".social-curator-dropdown-content").hide():(o(".social-curator-dropdown-content").hide(),o(t.currentDropdown).show()),o(t.currentDropdown).parents(".social-curator-dropdown").toggleClass("open")},t.windowListener=function(t){0==o(t).parents(".social-curator-dropdown").length&&(o(".social-curator-dropdown-content").hide(),o(".social-curator-dropdown").removeClass("open"))},t.closeAll=function(){o(".social-curator-dropdown-content").hide(),o(".social-curator-dropdown").removeClass("open")},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Logs=function(){var t=this,o=jQuery;return t.button="[data-social-curator-clear-logs]",t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.button,function(o){o.preventDefault(),t.clearLogs()})},t.clearLogs=function(){t.toggleLoading(!0),o.ajax({url:ajaxurl,type:"POST",data:{nonce:social_curator_admin.social_curator_nonce,action:"social_curator_clear_logs"},success:function(o){t.toggleLoading(!1),console.log(o),document.location.reload(!0)}})},t.toggleLoading=function(a){return a?(o(t.button).attr("disabled","disabled"),void o("[data-log-clear-loader]").css("display","inline-block")):(o(t.button).attr("disabled",!1),void o("[data-log-clear-loader]").css("display","none"))},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Avatar=function(){var t=this,o=jQuery;return t.button="[data-choose-avatar-image]",t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.button,function(){t.showMediaLibrary()})},t.showMediaLibrary=function(){return formfield=o("[data-fallback-avatar-field]").attr("name"),tb_show("","media-upload.php?type=image&TB_iframe=true"),!1},window.send_to_editor=function(t){imgurl=o("img",t).attr("src"),o("[data-avatar-image]").find("img").attr("src",imgurl),o("[data-fallback-avatar-field]").val(imgurl),tb_remove()},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.FeedTest=function(){var t=this,o=jQuery;return t.loadButton="[data-test-feed]",t.feedContainer="[data-test-feed-results]",t.site="",t.feedType="search",t.errorDiv="[data-test-feed-error]",t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.loadButton,function(a){a.preventDefault(),t.site=o(this).attr("data-site"),t.getFeed()}),o(document).on("change","[data-feed-type]",function(){var t=o("input[name=feed-type]:checked").val();return"single"===t?void o("[data-feed-id-container]").show():void o("[data-feed-id-container]").hide()})},t.getFeed=function(){t.loading(!0),o.ajax({url:ajaxurl,type:"POST",data:{nonce:social_curator_admin.social_curator_nonce,action:"social_curator_test_feed",site:t.site,type:o("input[name=feed-type]:checked").val(),format:o("input[name=feed-format]:checked").val(),id:o("[data-feed-id]").val()},success:function(a){"success"===a.status?t.populateFeed(a.feed):(o(t.errorDiv).text(a.message).show(),o(t.loading(!1)),o(t.feedContainer).hide())}})},t.populateFeed=function(a){var r=print_r(a);o(t.feedContainer).html("<pre>"+r+"</pre>"),t.loading(!1)},t.loading=function(a){return o(t.feedContainer).show(),a?(o(t.errorDiv).hide(),o(t.feedContainer).empty().addClass("loading"),void o(t.loadButton).attr("disabled","disabled").text(SocialCurator.localizedText.fetchingFeed)):(o(t.feedContainer).removeClass("loading"),void o(t.loadButton).attr("disabled",!1).text(SocialCurator.localizedText.testFeed))},t.init()};var print_r=function(t,o){var a=o||"",r="[object Array]"===Object.prototype.toString.call(t),e=r?"Array\n"+a+"[\n":"Object\n"+a+"{\n";for(var i in t)if(t.hasOwnProperty(i)){var n=t[i],s="",c=Object.prototype.toString.call(n);switch(c){case"[object Array]":case"[object Object]":s=print_r(n,a+"	");break;case"[object String]":s="'"+n+"'";break;default:s=n}e+=a+"	"+i+" => "+s+",\n"}return e=e.substring(0,e.length-2)+"\n"+a,r?e+"]":e+"}"},SocialCurator=SocialCurator||{};SocialCurator.Modals=function(){var t=this,o=jQuery;return t.modal_id="",t.init=function(){this.bindEvents()},t.bindEvents=function(){o(document).on("click","[data-social-curator-modal-open]",function(a){a.preventDefault(),t.modal_id=o(this).attr("data-social-curator-modal-open"),t.openModal()}),o(document).on("click","[data-social-curator-modal-close]",function(o){o.preventDefault(),t.closeModals()}),o(document).on("click","[data-social-curator-modal]",function(a){0===o(a.target).parents(".social-curator-modal-content").length&&t.closeModals()}),o(document).on("change","[data-social-curator-single-import-site]",function(){var t=o(this).val();o("[data-id-help-modal]").attr("data-social-curator-modal-open","id-help-"+t)})},t.openModal=function(){o('[data-social-curator-modal="'+t.modal_id+'"]').addClass("open")},t.closeModals=function(){o("[data-social-curator-modal]").removeClass("open")},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Alerts=function(){var t=this,o=jQuery;return t.button='[data-dismiss="alert"]',t.el="",t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.button,function(a){a.preventDefault(),t.el=o(this).parents(".social-curator-alert"),t.closeAlert()})},t.closeAlert=function(){o(t.el).fadeOut("fast")},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Masonry=function(){var t=this,o=jQuery;t.formatter=new SocialCurator.Formatter,t.triggerMasonry=function(a,r){if(o(SocialCurator.selectors.masonryContainer).masonry({itemSelector:SocialCurator.selectors.masonryItem,percentPosition:!0,gutter:".gutter-sizer"}),o(SocialCurator.selectors.masonryContainer).imagesLoaded(function(){o(SocialCurator.selectors.masonryContainer).masonry()}),a){if(r)return t.appendMasonry(a);t.prependMasonry(a)}},t.appendMasonry=function(t){setTimeout(function(){o(SocialCurator.selectors.masonryContainer).append(t).masonry("reload")},500)},t.prependMasonry=function(t){setTimeout(function(){o(SocialCurator.selectors.masonryContainer).prepend(t).masonry("reload")},500)},t.appendPosts=function(o,a){for(var a=a?!0:!1,r=[],e=0;e<o.length;e++)r[e]=t.buildSinglePost(o[e]);t.triggerMasonry(r,a),SocialCurator.resetLoading()},t.buildSinglePost=function(a){var r=o("[data-post-template]").find(SocialCurator.selectors.masonryItem).clone();if(o(r).find("[data-icon-link]").html(a.icon_link),o(r).find("[data-profile-image]").attr("src",a.profile_image_link),o(r).find("[data-profile-link]").attr("href",a.profile_link),o(r).find("[data-date]").text(a.date),o(r).find("[data-profile-name]").text(a.profile_name),o(r).find("[data-post-id]").attr("data-post-id",a.id),o(r).attr("data-post-container-id",a.id),a.thumbnail){var e='<img src="'+a.thumbnail+'" />';o(r).find("[data-thumbnail]").html(e)}if(a.content){var i=a.content;i+='<p><a href="'+a.edit_link+'">('+social_curator_admin.edit+")</a></p>",o(r).find("[data-post-content]").html(i)}return"publish"===a.status&&t.formatter.displayApproval(a,o(r)),"trash"===a.status&&t.formatter.displayTrashedButtons(a,o(r)),r[0]},t.removeGridItem=function(a){var r=o("[data-post-container-id="+a+"]");o(".social-curator-post-grid").masonry("remove",r),t.triggerMasonry()}};var SocialCurator=SocialCurator||{};SocialCurator.LoadMore=function(){var t=this,o=jQuery;return t.masonry=new SocialCurator.Masonry,t.selectors={loadMoreButton:"[data-social-curator-load-more]"},t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.selectors.loadMoreButton,function(o){o.preventDefault(),t.loadPosts()})},t.loadPosts=function(){t.loading(!0);var a="all"===o("[data-filter-status]").val()?null:o("[data-filter-status]").val(),r="all"===o("[data-filter-site]").val()?null:o("[data-filter-site]").val();o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.getposts,offset:SocialCurator.jsData.offset,number:SocialCurator.jsData.perpage,site:r,status:a},success:function(a){return console.log(a),SocialCurator.jsData.offset=SocialCurator.jsData.offset+SocialCurator.jsData.perpage,t.loading(!1),0===a.posts.length?void o("[data-social-curator-load-more]").attr("disabled","disabled").text("No More Posts"):void t.masonry.appendPosts(a.posts,!0)}})},t.loading=function(t){return t?o("[data-social-curator-grid-loading]").show():o("[data-social-curator-grid-loading]").hide()},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Import=function(){var t=this,o=jQuery;return t.masonry=new SocialCurator.Masonry,t.importingSite="",t.importingButton="",t.selectors={importAllButton:"[data-social-curator-import-all]",importSingleButton:"[data-social-curator-single-import]",importSingleSiteButton:"[data-import-site]",lastImportDate:"[data-social-curator-last-import]",importError:"[data-social-curator-import-error]",unmoderatedCount:"[data-social-curator-unmoderated-count]"},t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.selectors.importAllButton,function(o){o.preventDefault(),t.doImport()}),o(document).on("click",t.selectors.importSingleButton,function(a){a.preventDefault();var r=o("[data-social-curator-single-import-site]").val();t.doImport(r,!0)}),o(document).on("click",t.selectors.importSingleSiteButton,function(a){a.preventDefault(),t.importingSite=o(this).text(),t.importingButton=o(this);var r=o(this).attr("data-import-site");o(this).text(SocialCurator.localizedText.importing),t.doImport(r)})},t.doImport=function(a,r){if(o(t.selectors.importError).parents(".social-curator-alert-error").hide(),SocialCurator.toggleLoading(!0),o("[data-import-site], [data-social-curator-single-import]").attr("disabled","disabled"),o("[data-social-curator-import-all], [data-social-curator-single-import]").attr("disabled","disabled").text(social_curator_admin.importing),!a)var a="all";var e={nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.doImport,site:a};r&&(e.action=SocialCurator.formActions.singleImport,e.id=o("[data-social-curator-single-import-id]").val()),o.ajax({url:ajaxurl,type:"POST",data:e,success:function(a){console.log(a),t.resetImportButtons(),"success"===a.status?(a.import_count>30&&document.location.reload(!0),SocialCurator.toggleLoading(!1),SocialCurator.dropdowns.closeAll(),o(t.selectors.lastImportDate).text(a.import_date),t.updateLastImportCount(a),t.getNewPosts(a.posts)):t.displayImportError(a.message)}})},t.getNewPosts=function(a){return 0===a.length?void SocialCurator.toggleLoading(!1):void o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.getposts,posts:a,status:["pending"],adminview:!0},success:function(o){t.updateUnmoderatedCount(o.unmoderated_count),t.masonry.appendPosts(o.posts)}})},t.updateLastImportCount=function(t){o("[data-social-curator-import-count]").text(t.import_count),o("[data-social-curator-import-site]").text(t.site),o("[data-social-curator-import-count]").parents(".social-curator-alert").show()},t.updateUnmoderatedCount=function(a){o(t.selectors.unmoderatedCount).text(a)},t.displayImportError=function(a){o(t.selectors.importError).text(a).parents(".social-curator-alert-error").show(),SocialCurator.resetLoading()},t.resetImportButtons=function(){""!==t.importingSite&&(t.importingButton.text(t.importingSite),t.importingSite="",t.importingButton=""),o(t.selectors.importSingleSiteButton+","+t.selectors.importSingleButton+","+t.selectors.importAllButton).attr("disabled",!1)},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Trash=function(){var t=this,o=jQuery;return t.postID="",t.formatter=new SocialCurator.Formatter,t.masonry=new SocialCurator.Masonry,t.selectors={restorePostButton:"[data-restore-post]",deletePostButton:"[data-permanent-delete-post]"},t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.selectors.restorePostButton,function(a){a.preventDefault(),o(this).attr("disabled","disabled"),t.postID=o(this).attr("data-post-id"),t.restorePost()}),o(document).on("click",t.selectors.deletePostButton,function(a){a.preventDefault(),o(this).attr("disabled","disabled"),t.postID=o(this).attr("data-post-id"),t.deletePost()})},t.restorePost=function(){SocialCurator.toggleLoading(!0),o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.restorePost,post_id:t.postID},success:function(a){t.formatter.addUnmoderated(),t.masonry.removeGridItem(t.postID),SocialCurator.toggleLoading(!1),o("[data-trash-count]").text(parseInt(o("[data-trash-count]").text())-1)}})},t.deletePost=function(){SocialCurator.toggleLoading(!0),o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.deletePost,post_id:t.postID},success:function(a){t.masonry.removeGridItem(t.postID),SocialCurator.toggleLoading(!1),o("[data-trash-count]").text(parseInt(o("[data-trash-count]").text())-1)}})},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Approve=function(){var t=this,o=jQuery;return t.masonry=new SocialCurator.Masonry,t.formatter=new SocialCurator.Formatter,t.postID="",t.selectors={approveButton:"[data-approve-post]"},t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.selectors.approveButton,function(a){a.preventDefault(),o(this).attr("disabled","disabled"),t.postID=o(this).attr("data-post-id"),t.approvePost()})},t.approvePost=function(){SocialCurator.toggleLoading(!0),o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.approvePost,post_id:t.postID},success:function(o){t.formatter.displayApproval(o),t.formatter.subtractUnmoderated(),t.masonry.triggerMasonry(),SocialCurator.toggleLoading(!1)}})},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.TrashPost=function(){var t=this,o=jQuery;return t.formatter=new SocialCurator.Formatter,t.masonry=new SocialCurator.Masonry,t.button="",t.selectors={trashButton:"[data-trash-post]",trashCount:"[data-trash-count]"},t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.selectors.trashButton,function(a){a.preventDefault(),o(this).attr("disabled","disabled"),t.button=o(this),t.trashPost()})},t.trashPost=function(){SocialCurator.toggleLoading(!0);var a=o(t.button).attr("data-post-id");o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.trashPost,post_id:a},success:function(){o(t.selectors.trashCount).text(parseInt(o(t.selectors.trashCount).text())+1),SocialCurator.toggleLoading(!1),t.updateGrid()}})},t.updateGrid=function(){o(t.button).parents(".social-curator-post-grid-single").hasClass("approved")||t.formatter.subtractUnmoderated(),o(t.button).parents(".social-curator-post-grid-single").fadeOut("fast",function(){o(".social-curator-post-grid").masonry("remove",o(this)),t.masonry.triggerMasonry()})},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Filter=function(){var t=this,o=jQuery;return t.masonry=new SocialCurator.Masonry,t.selectors={filterButton:"[data-filter-grid]"},t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.selectors.filterButton,function(o){o.preventDefault(),t.filterPosts()})},t.filterPosts=function(){SocialCurator.toggleLoading(!0),o("[data-post-grid]").find(".social-curator-post-grid-single").remove(),o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.getposts,status:o("[data-filter-status]").val(),site:o("[data-filter-site]").val(),offset:0,number:SocialCurator.jsData.perpage},success:function(a){o("[data-social-curator-load-more]").attr("disabled",!1).text("Load More"),t.masonry.appendPosts(a.posts)}})},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.Grid=function(){var t=this,o=jQuery;return t.masonry=new SocialCurator.Masonry,t.loadMore=new SocialCurator.LoadMore,t.importer=new SocialCurator.Import,t.trashPost=new SocialCurator.TrashPost,t.filter=new SocialCurator.Filter,t.trash=new SocialCurator.Trash,t.approve=new SocialCurator.Approve,t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).ready(function(){t.masonry.triggerMasonry()})},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.EmptyTrash=function(){var t=this,o=jQuery;return t.selectors={trashButton:"[data-empty-social-trash]",trashCount:"[data-trash-count]"},t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.selectors.trashButton,function(a){a.preventDefault(),window.confirm("Are you sure you want to empty the trash?")&&(o(this).attr("disabled","disabled"),t.emptyTrash())})},t.emptyTrash=function(){SocialCurator.toggleLoading(!0),o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.emptyTrash},success:function(a){SocialCurator.toggleLoading(!1),o(t.selectors.trashCount).text("0"),o(t.selectors.trashButton).attr("disabled",!1),"trash"===o("[data-filter-status]").val()&&o("[data-post-grid]").find(".social-curator-post-grid-single").remove()}})},t.init()};var SocialCurator=SocialCurator||{};SocialCurator.PostColumns=function(){var t=this,o=jQuery;return t.button="",t.selectors={moderateButton:"[data-social-curator-moderate-select-button]",moerateSelect:"[data-social-curator-moderate-select]"},t.init=function(){t.bindEvents()},t.bindEvents=function(){o(document).on("click",t.selectors.moderateButton,function(a){a.preventDefault(),t.button=o(this),o(this).attr("disabled","disabled").text(SocialCurator.localizedText.updating),t.moderatePost()})},t.moderatePost=function(){var a=o(t.button).attr("data-post-id"),r=o(t.button).siblings("[data-social-curator-moderate-select]").val();o.ajax({url:ajaxurl,type:"POST",data:{nonce:SocialCurator.jsData.nonce,action:SocialCurator.formActions.updateStatus,post_id:a,status:r},success:function(a){o(t.button).attr("disabled",!1).text(SocialCurator.localizedText.update),"trash"===r&&o(t.button).parents("tr").fadeOut("fast",function(){o(this).remove()})}})},t.init()},jQuery(document).ready(function(){new SocialCurator.Factory});var SocialCurator=SocialCurator||{};SocialCurator.selectors={loadingIndicator:"[data-curation-loader]",masonryContainer:".social-curator-post-grid",masonryItem:".social-curator-post-grid-single"},SocialCurator.jsData={nonce:social_curator_admin.social_curator_nonce,offset:10,perpage:10,importingsite:""},SocialCurator.localizedText={runImport:social_curator_admin.run_import,importing:social_curator_admin.importing,importText:social_curator_admin["import"],importAll:social_curator_admin.import_all,approvedBy:social_curator_admin.approved_by,on:social_curator_admin.on,edit:social_curator_admin.edit,permanentlyDelete:social_curator_admin.permanently_delete,restore:social_curator_admin.restore,updating:social_curator_admin.updating,update:social_curator_admin.update,chooseImage:social_curator_admin.choose_image,unapproveTrash:social_curator_admin.unapprove_and_trash,fetchingFeed:social_curator_admin.fetching_feed,testFeed:social_curator_admin.test_feed},SocialCurator.formActions={getposts:"social_curator_get_posts",emptyTrash:"social_empty_trash",doImport:"social_curator_manual_import",singleImport:"social_curator_single_import",trashPost:"social_curator_trash_post",restorePost:"social_curator_restore_post",deletePost:"social_curator_delete_post",approvePost:"social_curator_approve_post",updateStatus:"social_update_post_status"},SocialCurator.dropdowns=new SocialCurator.Dropdowns,SocialCurator.resetLoading=function(){var t=jQuery;t(SocialCurator.selectors.loadingIndicator).hide(),SocialCurator.dropdowns.closeAll(),t("[data-social-curator-import-all], [data-social-curator-single-import]").attr("disabled",!1).text(SocialCurator.localizedText.runImport),t("[data-import-site], [data-social-curator-single-import]").attr("disabled",!1),t("[data-social-curator-single-import-id]").val("")},SocialCurator.toggleLoading=function(t){var o=jQuery;return t?void o("[data-curation-loader]").show():void o("[data-curation-loader]").hide()},SocialCurator.Factory=function(){var t=this;t.logs=new SocialCurator.Logs,t.avatars=new SocialCurator.Avatar,t.feedTest=new SocialCurator.FeedTest,t.modals=new SocialCurator.Modals,t.bulkimport=new SocialCurator.BulkImport,t.alerts=new SocialCurator.Alerts,t.grid=new SocialCurator.Grid,t.emptyTrash=new SocialCurator.EmptyTrash,t.postColumns=new SocialCurator.PostColumns};