jQuery(function(a){function o(){c(!0),a("[data-social-curator-manual-import]").attr("disabled","disabled").text(social_curator_admin.importing),a.ajax({url:ajaxurl,type:"POST",data:{nonce:social_curator_admin.social_curator_nonce,action:"social_curator_manual_import"},success:function(a){t(a)}})}function t(o){console.log(o),c(!1),a("[data-social-curator-manual-import]").attr("disabled",!1).text(social_curator_admin.run_import)}function c(o){o?a("[data-curation-loader]").show():a("[data-curation-loader]").hide()}a(document).on("click","[data-social-curator-manual-import]",function(a){a.preventDefault(),o()})});