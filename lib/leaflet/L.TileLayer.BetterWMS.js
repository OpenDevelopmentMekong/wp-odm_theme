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
    };
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
    if (err) { console.log(err); return; } // do nothing if there's an error
         console.log(content);
    if (content.features.length>0){
    // Otherwise show the content in a popup, or something.
         L.popup({ maxWidth: 800})
          .setLatLng(latlng)
          .setContent(buildpopup(content))
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

//added H.E
function buildpopup(content){
    var record; var full_name_of_filename
    var info = "<div class=\"wmspopupinfo\">";
    for (var i=0 ; i < content.features.length; i++ ){
        record = content.features[i];
        info += "<div class=\"popupinfo\">";
        var exclude = ["map_id","language","geo_type", "legal_documents", "land_utilization_plan", "published_status", "last_update"];
        var include = ["commune", "cassava_ha", "rbuff", "rcow", "(2006) name", "lsize_ha", "hectares", "name", "village", "distance,phyvio", "sexvio", "menvio,hhecovio", "tt_dom_vio,fishd_com", "province", "aq_pro", "tt", "size_ha", "per_hh_el,x", "y,tt_family", ",t_lit15", "m_lit15", "", "block", "ssize_skm", "operator", "co_oper", "rifield_ha", "commune_s", "cashew_ha", "casheyield", "mango_ha", "mangoyeild,rpig", "tot_hh", "rpoultry", "comm_code,acronym", "size_kv,dist_line", "status", "descrip", "totpop", "year", "tt_family", "zone", "consump"];

        var detect_lang_site = document.documentElement.lang;
        if(detect_lang_site == "en-US"){
          var full_name_of_filename = {"commune": "Commune", "cassava_ha": "Cassava (Hetare)", "rbuff": "Ratio of Buffalo raised per family", "rcow": "Ratio of Cow raised per family", "name": "Name", "lsize_ha": "Land Size (Ha)", "hectares": "Hectares", "village": "Village Name", "distance": "Distance from Village to Secondary School","phyvio":"Physical Violence", "sexvio": "Sexual Violence", "menvio": "Mental Violence", "hhecovio": "Household Economic Violence", "tt_dom_vio": "Total of Domestic violence", "fishd_com": "Fish Dependency by Commune", "province": "Province", "aq_pro": "Aquaculture Production", "tt": "Total Fish Production", "size_ha": "Land Size (Ha)", "per_hh_el": "Percentage Household with Electricity", "x": "X", "y": "Y", "tt_family": "Total of Family", "t_lit15": "Total Literacy over 15", "block": "Block", "ssize_skm": "Size", "operator": "Operator", "co_oper": "Cooperator", "rifield_ha": "Rice Field Size (Ha)", "commune_s": "Commune Size", "cashew_ha": "Cashew Production (Ha)", "casheyield": "Cassava Yield per year", "mango_ha": "Mango Production (Ha)", "mangoyeild": "Mango Yield per year", "rpig": "Ratio of Pig raised per family", "tot_hh": "Total population of Household", "rpoultry": "Ratio of Buffalo raised per family", "Year_ReLoc ": "Year of Relocation", "acronym": "Province acronym", "size_kv": "Size_kv", "dist_line": "Distance (Km)", "status": "Status", "descrip": "Description", "totpop": "Total Population", "year": "year", "tt_family": "Total of Family", "zone": "Zone", "consump": "Consumption", "COM_CODE ": "Commune Code"};
        } else {//if(detect_lang_site == "kh" || detect_lang_site == "km"){
          var full_name_of_filename = {"commune": "ឃុំ/សង្កាត់", "cassava_ha": "ផ្ទៃដីសម្រាប់ទិន្នផលដំឡូងមី (ហិចតា)", "rbuff":"អត្រាក្របីចិញ្ចឹមធៀបនឹងកសិដ្ឋានចិញ្ចឹមក្របី", "rcow": "អត្រាគោចិញ្ចឹមធៀបនឹងកសិដ្ឋានចិញ្ចឹមគោ " , "name": "ឈ្មោះ", "lsize_ha": "ទំហំដី (ហិចតា)", "hectares": "ទំហំដី (ហិចតា)", "village": "ឈ្មោះភូមិ", "distance": "ចំងាយពីភូមិទៅកាន់អនុវិទ្យាល័យ", "phyvio":"អំពើហិង្សាលើផ្លូវកាយ", "sexvio": "អំពើហិង្សាលើផ្លូវភេទ ", "menvio": "អំពើហិង្សាលើផ្លូវចិត្ត", "hhecovio": "អំពើហិង្សាលើសេដ្ឋកិច្ចគ្រួសារ", "tt_dom_vio": "ចំនួនអំពើហិង្សាសរុប", "fishd_com": "ឃុំពឹងផ្អែកទៅលើផលិតផលត្រី", "province": "ខេត្ត", "aq_pro": "ទិន្នផលធនធានជលផលទឹកសាប", "tt": "ទិន្នផលសរុប", "size_ha": "ទំហំដី (ហិចតា) ", "per_hh_el": "អត្រាភាគរយគ្រួសារមានអគ្គិសនីប្រើប្រាស់", "x": "អាប់ស្សិស", "y": "អរដោនេ", "tt_family": "ចំនួនគ្រួសារសរុប",  "t_lit15": "អក្ខរកម្មសរុបលើ ១៥ ឆ្នាំ", "block": "ប្លុក", "ssize_skm": "ទំហំ", "operator": "ក្រុមហ៊ុន", "co_oper": "ក្រុមហ៊ុនសហការ", "rifield_ha": "ទំហំផ្ធៃដីដាំស្រូវ(ហិចតា)",  "commune_s": "ទំហំដីឃុំ", "ratio":  "រាត្យូ", "cashew_ha": " ផ្ទៃដីសម្រាប់ទិន្នផលដំឡូងមី (ហិចតា)", "casheyield": "ទិន្នផលដំឡូងមីក្នុងមួយឆ្នាំ (តោន/ហិចតា)", "mango_ha": "ផ្ទៃដីសម្រាប់ទិន្នផលស្វាយ (ហិចតា)", "mangoyeild": "ទិន្នផលស្វាយក្នុងមួយឆ្នាំ (តោន/ហិចតា)", "rpig": "អត្រាជ្រូកចិញ្ចឹមធៀបនឹងកសិដ្ឋានចិញ្ចឹមជ្រូក", "tot_hh": "ចំនួនគ្រួសារសរុប", "rpoultry": "អត្រាសត្វស្លាបចិញ្ចឹមធៀបនឹងកសិដ្ឋានចិញ្ចឹមសត្វស្លាប", "Year_ReLoc ": "ឆ្នាំនៃការផ្លាស់ទីលំនៅថ្មី",  "acronym": "អក្សរកាត់ខេត្ត", "size_kv": "ទំហំតង់ស្យង(គីឡូវុល)", "dist_line": "ចំងាយ(គីឡូម៉ែត)", "status": "ស្ថានភាព", "descrip": "ព័ត៍មានបន្ថែម", "totpop": "ចំនួនប្រជាជនសរុប", "year": "year", "tt_family": "ចំនួនគ្រួសារសរុបនៅក្នុងឃុំ/សង្កាត់", "zone": "ស៊ូន", "consump": "ការបរិភោគត្រី", "COM_CODE ": "លេខកូដឃុំ/សង្កាត"};
        }

        for (var name in record.properties) {
              //if ( $.inArray(name, exclude) == -1 ) {
              if ( $.inArray(name, include) > -1 ) {
                // var field_name = name.substr(0, 1).toUpperCase() + name.substr(1);
                console.log(full_name_of_filename);
                 var field_name = full_name_of_filename[name];
                 if (typeof(field_name) == "undefined"){
                    field_name = name;
                 }
                 var field_value = record.properties[name] ;

                     // Set the regex string
                     var regexp = /(https?:\/\/([-\w\.]+)+(:\d+)?(\/([-\w\/_\.]*(\?\S+)?)?)?)/ig;
                     // Replace plain text links by hyperlinks
                    if (regexp.test(field_value)){
                        var field_url_value = field_value.split(";");
                        console.log(field_url_value.length);
                        var url_doc = "";
                        for(var url =0; url < field_url_value.length; url++ ){
                             url_doc = url_doc + field_url_value[url].replace(regexp, "<a href='$1' target='_blank'>$1</a><br />");
                        }
                        field_value = url_doc;
                    }
                    if(name == "name"){
                        info += "<h3>"+field_value+"</h3>";
                    }else{
                        info += "<strong>"+field_name+"</strong>: "+field_value+"<br />";
                    }
              }
        }
        info+="</div>";
        if (i!= (content.features.length-1)){
            info += "<br />";
        }
    }
    info += "</div>"


    return info;

}
