var selectedFeature, queryCoordinates;
L.TileLayer.BetterWMS = L.TileLayer.WMS.extend({

  onAdd: function (map) {
    // Triggered when the layer is added to a map.
    //   Register a click listener, then do all the upstream WMS things
    L.TileLayer.WMS.prototype.onAdd.call(this, map);
    map.on('click', this.getFeatureInfo, this);
  },

  onRemove: function (map) {
    // Triggered when the layer is removed from a map.
    //   Unregister a click listener, then do all the upstream WMS things
    L.TileLayer.WMS.prototype.onRemove.call(this, map);
    map.off('click', this.getFeatureInfo, this);
  },

  getFeatureInfo: function (evt) {
    // Make an AJAX request to the server and hope for the best
    if (selectedFeature) {
        map.removeLayer(selectedFeature);
    }
    queryCoordinates = evt.latlng;
    var url = this.getFeatureInfoUrl(evt.latlng),
        showResults = L.Util.bind(this.showGetFeatureInfo, this);
    $.ajax({
      url: url,
      dataType : 'jsonp',
      jsonpCallback : 'getJson',
      success: function (data, status, xhr) {
        var err = typeof data === 'object' ? null : data;
       // var err = typeof data === 'string' ? null : data;
        showResults(err, evt.latlng, data);
      },
      error: function (xhr, status, error) {
           console.log(xhr);
        showResults(error);
      }
    });

  },

  getFeatureInfoUrl: function (latlng) {
    // Construct a GetFeatureInfo request URL given a point
    var point = this._map.latLngToContainerPoint(latlng, this._map.getZoom()),
        size = this._map.getSize(),
        params = {
        request: 'GetFeatureInfo',
        service: 'WMS',
        srs: 'EPSG:4326',
        styles: this.wmsParams.styles,
        transparent: this.wmsParams.transparent,
        version: this.wmsParams.version,
        format: this.wmsParams.format,
        bbox: this._map.getBounds().toBBoxString(),
        height: size.y,
        width: size.x,
        layers: this.wmsParams.layers,
        query_layers: this.wmsParams.layers,
        // INFO FORMAT JSON
	      info_format: 'text/javascript',
        outputFormat : 'text/javascript',
        format_options : 'callback:getJson'
      };

    params[params.version === '1.3.0' ? 'i' : 'x'] = point.x;
    params[params.version === '1.3.0' ? 'j' : 'y'] = point.y;
    return this._url + L.Util.getParamString(params, this._url, true);
  },

  showGetFeatureInfo: function (err, latlng, content) {
    var info_title = this.wmsParams.info_title;
    var info_attributes = this.wmsParams.info_attributes;
    var info_detail = this.wmsParams.info_detail;
    if (err) { console.log(err); return; } // do nothing if there's an error

    // Otherwise show the content in a popup, or something.
    if (content.features.length>0){
         L.popup({ maxWidth: 800})
          .setLatLng(latlng)
          .setContent(buildpopup(content, info_title, info_attributes, info_detail))
          .openOn(this._map);

     }else {		// Optional... show an error message if no feature was returned
		  $("#daneben").fadeIn(750);
		  setTimeout(function(){ $("#daneben").fadeOut(750); }, 2000);
      }
    }
});

L.tileLayer.betterWms = function (url, options) {
  return new L.TileLayer.BetterWMS(url, options);
};

function buildpopup(content, info_title, info_attributes, info_detail){
  if( ((info_title !== null) && (info_title != "")) || ((info_attributes !== null) && (info_attributes != "")) || ((info_detail !== null) && (info_detail != ""))){
    var record;
    var regexp = /(https?:\/\/([-\w\.]+)+(:\d+)?(\/([-\w\/_\.]*(\?\S+)?)?)?)/ig;
    var translations = {
      en:  { view_detail: "View detail"},
      km:  { view_detail: "មើលលម្អិត" },
      my:  { view_detail: "ကြည့်ရန်အသေးစိတ်" },
      lo:  { view_detail: "ເບິ່ງ​ລາຍ​ລະ​ອຽດ" },
      th:  { view_detail: "ดูรายละเอียด" },
      vi:  { view_detail: "Xem chi tiết" }
    };

    var view_detail_label = translations.en.view_detail;
    if(document.documentElement.lang != "en-US"){
      view_detail_label = translations[ detect_lang_site ].view_detail;
    }
    var info = "<div class=\"wmspopupinfo\">";
    for (var i=0 ; i < content.features.length; i++ ){
        record = content.features[i];

        if((info_title !== null) && (info_title != "") ){
          if(info_title in record.properties){
            info += "<h3>"+ record.properties[info_title]+"</h3>";
          }else{
            info += "<h3>"+ info_title +"</h3>";
          }
        }

        info += "<div class='popupinfo popupinfo-'+ >";
        if((info_attributes !== null) && (info_attributes != "") ){
          for(var info_attr in info_attributes){
            if(info_attr in record.properties){
              var field_value = record.properties[info_attr];
              // Replace plain text links by hyperlinks
             if (regexp.test(field_value)){
                 var field_url_value = field_value.split(";");
                 var url_doc = "";
                 for(var url =0; url < field_url_value.length; url++ ){
                      url_doc = url_doc + "- "+field_url_value[url].replace(regexp, "<a class='related_doc' href='$1' target='_blank'>$1</a><br />");
                 }
                 field_value = "<br/>"+url_doc.replace(/^\s*<br\s*\/?>|<br\s*\/?>\s*$/g,'');;
             }

                info += info_attributes[info_attr]+": "+field_value+"<br />";
            }
          }
        }


        if((info_detail !== null) && (info_detail != "") ){
          var view_detail_link = info_detail.split("{{");
          if(view_detail_link){
              var view_detail = view_detail_link[0];
              if(regexp.test(view_detail)){
                var detail_link = view_detail;
              }else{
                if(view_detail in record.properties){
                   var detail_link = record.properties[view_detail]
                }
              }

              if( view_detail_link[1]){
                var attribute_name = view_detail_link[1].replace("}}", "");
                if(attribute_name in record.properties){
                   detail_link =detail_link + record.properties[attribute_name]
                }
              }

              info += "<a class='view-detail' href='"+detail_link+"' target='_blank'>"+view_detail_label+"</a>";
            }


        }

        for (var name in record.properties) {
        }

        info+="</div>";
        if (i!= (content.features.length-1)){
            info += "<br />";
        }
    }
    info += "</div>";
    return info;
  }


}
