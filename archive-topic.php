<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('Topics','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">

    <div class="row">

      <div class="eleven columns">

				<h2 class="clearfix"><?php _e('Environment and land','odm'); ?></h2>

        <?php

					$post = get_post();
					$post_categories = get_categories_array($post);
					while (have_posts()) : the_post();
						$taxonomy_categories = array("Fish farming and aquaculture","Community fisheries","Wild capture commercial fishing and natural fisheries","Fisheries production","Fishing policy and administration","Fishing","fisheries and aquaculture","Sugarcane","Soybean","Rubber","Rice","Palm oil","Maize (corn)","Fruits and vegetables","Cashews","Sugar bagasse","Jatropha","Cassava","Biofuel crops","Crop products and commodities","Poultry and eggs","Leather","Meat","Dairy","Animal products","Agricultural commodities","processing and products","Irrigation and water management","Soil management","Pest management","Agricultural engineering services and equipment","Agricultural management systems and technologies","Small and medium scale farming","Organic farming","Livestock farming","Economic land concessions and plantations","Contract farming","Agro-industrial production","Agricultural production","Agricultural policy and administration","Agriculture","Agriculture and fishing","Pandemics","Tsunamis","Earthquakes","Storms","Floods","Fires","Drought","Disasters","Disaster and emergency response NGOs","Disasters and emergency response funding","Red Cross","Non-governmental preparedness and response agencies","Disaster preparedness and emergency response policy and administration","Disasters and emergency response","Marine and coastal areas","Rivers and lakes","Ground water","Water rights","Water policy and administration","Water resources","National parks and wildlife sanctuaries","Sustainable use forest","Community forest","Protected forests","Protected areas","Forest protection NGOs","Forest protection funding","Forest protection support","Forest protection","Plantation timber","Non-timber forest products","Forest trade and finance","Pine","Eucalyptus","Acacia","Paper and pulp","Hardwoods","Tree plantation","Logging and timber","Forest industry","Forest products","Secondary/mixed forest","Regenerated forest","Dense forest","Terms and definitions","Forest classifications","Deforestation drivers","Forest cover reporting","Forest cover","Forest policy and administration","Forests and forestry","Electronic waste","Solid waste","Water pollution","Air pollution","Pollution and waste","Environmental and biodiversity protection","Nationally Appropriate Mitigation Action","Clean development mechanism","Mitigation","Adaptation","Climate change","Environmental impact assessments","Types of state-protected areas","Relevant ministries","Overview of policy and legal framework","Environment and natural resources policy and administration","Ecosystems","Plants","Animals","Biodiversity","Ecological services","Environment and natural resources","Oil transport","Oil refineries","Off-shore oil and gas exploration and extraction","On-shore oil and gas exploration and extraction","Oil and gas resources","Uranium","Iron and steel","Gravel and limestone","Gold","Gemstones","Copper","Coal","Minerals and mineral products","Quarrying","Industrial mining","Gemstone mining","Artisanal mining","Mining","Extractive industries revenue","Extractive industries licensing and payments","Extractive industries policy and administration","Extractive industries","Landmines","UXO and demining","Social land concessions","Communal land","Special economic zones","Concessions","Public land lease","Expropriation","Land sales and trades","Land transfers","Land tenure and land titling NGOs","Land tenure and land titling funding","Development and assistance for land tenure and land titling","Land tenure and land titling","Private land","State private land","State public land","Land classifications","Land policy and administration","Land");
						if (count(array_intersect($post_categories,$taxonomy_categories)) > 0):
							odm_get_template('post-grid-single-4-cols',array(
		  					"post" => $post,
		  					"show_meta" => true),true);
						endif;
					endwhile; ?>

				<?php rewind_posts(); ?>

				<h2 class="clearfix"><?php _e('Economy','odm'); ?></h2>

				<?php
					while (have_posts()) : the_post();
						$taxonomy_categories = array("Stock market","Securities exchange policy and regulation","Securities exchange (stock market)","Exports","Imports","Trade policy and regulation","Trade","SMEs policy and regulation","Small and medium enterprises (SME)","Foreign investors","Domestic investors","Investment policy and regulations","Investment","Chinese business associations","Local chamber of commerce","Australian Chamber of Commerce","American Chambers of Commerce","Business associations","Micro-finance","Major banks","Banking and financial services policy and regulation","Banking and financial services","Business structures and legal registration","Economic policy and administration","Economy and commerce","Cooking fuel","Energy for transport","Renewable energy production","Hydropower dams","Non-renewable energy production","Electricity production","Energy policy and administration","Energy","Urban sanitation infrastructure","Urban water supplies and distribution","Urban water and sanitation infrastructure","Rural sanitation","Rural water supply","Rural water and sanitation infrastructure","Water and sanitation infrastructure and facilities","Water and sanitation NGOs","Water and sanitation funding","Development assistance for water and sanitation","Water quality testing","Water and sanitation policy and administration","Water and sanitation","Development and funding for transport","Waterways and ports","Rail","Roads and bridges","Airports and air travel","Transport infrastructure and facilities","Transport and shipping policy and administration","Transport and shipping","Vendor associations","Vendor rights","Vendors","Markets policy and regulation","Markets","Development and funding for electricity","Generation and distribution facilities","Electricity policy and administration","Electricity infrastructure","Development and funding for communications","Television","Mobile","Landline","Telephone","Radio","Internet","Communications infrastructure","Communications policy and administration","Communications","Infrastructure","Gaming","Tourism","Real estate","Steel","Oil","Cement","Bricks and tiles","Mineral processing and products","Cotton and silk","Shoes","Clothes","Garments and textiles","Furniture policy and regulation","Furniture","Salt","Processed foods","Milk and milk products","Meat and meat products","Fish and seafood","Beverages","Food processing","Cars","Rubber","Fertilizer","Biofuels","Animal feed","Agricultural processing","Manufacturing policy and regulation","Manufacturing","Handicrafts industry assistance NGOs","Handicrafts industry assistance funding","Development and assistance for handicrafts industry","Handicrafts industry policy and regulation","Handicrafts","Major companies and projects","Construction policy and regulation","Construction","Industries","Labor rights and labor unions NGOs","Labor rights and labor unions funding","Development assistance for labor rights and labor unions","Labor arbitration","Unions","Commercial sex labor","Child labor","Human trafficking","Illegal labor","Offshore recruitment and employment","Informal labor","Formal labor","Protection for overseas workers","Worker rights","Safety and health at work","Minimum wage","Labor policy and administration","Labor","Science and technology education and promotion","Space technologies","Open technologies","Material science","Atomic energy and nuclear technology","Technologies","Science and technology research and development","Science and technology policy and administration","Science and technology");
						if (count(array_intersect($post_categories,$taxonomy_categories)) > 0):
							odm_get_template('post-grid-single-4-cols',array(
		  					"post" => $post,
		  					"show_meta" => true),true);
						endif;
					endwhile; ?>

				<?php rewind_posts(); ?>

				<h2 class="clearfix"><?php _e('People','odm'); ?></h2>

				<?php
					while (have_posts()) : the_post();
						$taxonomy_categories = array("Private non-profit development assistance","Malaysian aid","Indian aid","Chinese aid","Non-DAC members","United States aid","Swedish aid","South Korean aid","Japanese aid","German aid","European Union aid","Australian aid","Development Assistance Committee (DAC) members","Bilateral development assistance","World Bank","United Nations","International Monetary Fund (IMF)","Food and Agriculture Organization (FAO)","China Development Bank (CDB)","Asian Development Bank (ADB)","Multilateral development assistance","Development policy and administration","Aid and development","International relations","Anti-corruption","Procurement","Government services","Taxation","Budget","Elections","Decentralization and deconcentration","Administration","Provincial and local governments","Ministries and other national bodies","Parliament","Executive","Head of state","National government","System of government","Government","Prison monitoring","Prisoner rights","Prisons policy and administration","Prisons","Police","Traffic violation","Crimes","Crime and law enforcement","Legal education for the public","Court clerk education","Lawyer education","Judge education","Legal education for professionals","Public and administrative legal services","Bar Association","Regulation of court clerks","Regulation of lawyers","Regulation of judges","Regulation of legal professions","Legal professions and services","Legal and judicial reform NGOs","Legal and judicial reform funding","Development and assistance for judicial reform","Legal and judicial reform","Legal aid NGOs","Bar Association and legal aid services","Legal aid providers","Legal aid policy and regulation","Legal aid","Alternative dispute resolution","Civil and commercial litigation","Criminal litigation","Court monitoring","Judiciary and court structure","Law on judiciary","Judiciary and courts","Legal system and judicial system","Rights of the accused","Laws and regulations","International laws and treaties ","Legal framework","Law making process","Constitution and rights","Law and judiciary","Provincial profiles","Censuses","Local workers abroad","Foreign workers in country","Informal migration for labor","Emigration","Immigration","Migration","Demographics","Population","Population and censuses","Sports leagues","Public sports venues","Sports development and assistance NGOs","Sports development and assistance funding","Development and assistance for sports","Sports education and training","Sports policy and administration","Sport","Social security and veterans policy and administration","Social security and veterans","Public health assistance NGOs","Public health assistance funding","Development assistance for health sector","Tuberculosis","Maternal and child health","Malaria","HIV/AIDS","Avian flu (bird flu)","Priority health concerns","Pharmaceuticals","Medical professional associations","Medical certification and regulation","Medical education and training","Medical professionals","Mental health and social services","Hospitals and clinics","Patient rights","Health care policy and administration","Public health","Poverty reduction and food securities assistance NGOs","Poverty reduction and food securities assistance funding","Development assistance for poverty reduction and food security","Access to education and health care","Food security","Poverty reduction","Poverty policy and regulation","The poor","Human rights associations","Human rights NGOs","Human rights funding","Development and assistance for human rights","Livelihood rights","Minority religions","Buddhist clergy rights","Religious freedom","Land and housing rights and evictions","Free","prior and informed consent (FPIC)","Economic","social and cultural rights","Access to information","Freedom of association","Freedom of expression","Civil and political rights","Human rights","Women?'s associations","Women's development NGOs","Women's development funding","Development and assistance for women","Women in the family","Women in development","Women in government and politics","Women?s rights","Women policy and administration","Women","LGBTQ associations","LGBTQ development NGOs","LGBTQ funding","Development and assistance for LGBTQ community","LGBTQ policy and rights","Lesbian","gay","bi-sexual","transgender","and queer (LGBTQ)","Gender","Youth associations","families","children and youth development NGOs","Families","children and youth funding","Development and assistance for families","children and youth","Orphans and children without homes","Marriage and divorce","Domestic violence","Access to education","Child labor","Child rights","Family","children","and youth policy and administration","Family","children and youth","Ethnic minorities and indigenous people associations","Ethnic minorities and indigenous people development NGOs","Ethnic minorities and indigenous people development funding","Development and assistance for ethnic minorities and indigenous people","Ethnic minorities and indigenous people policy and rights","Ethnic minorities and indigenous people profiles","Ethnic minorities and indigenous people","Education associations","Education NGOs","Education funding","Development and assistance for education","Adult literacy","Study abroad","Graduate programs","Universities","Higher education","Vocational education","Primary and secondary education","Pre school","Education policy and administration","Education and training","Disabled persons assistance NGOs","Disabled persons assistance funding","Development and assistance for disabled persons","Disabled persons rights","Disabled persons policy and administration","Disabled persons","Civil society coalitions and cooperation","Associations and other membership groups","Non-governmental organizations","Civil society policy and regulation","Civil society","Seniors associations","The aged assistance NGOs","The aged assistance funding","Development and assistance for the aged","The aged policy and administration","The aged","Arts associations","Arts NGOs","Arts funding","Arts support","Arts education","Arts policy and administration","Arts","Social development","City profiles","Urban policy and administration","Urban development trends","Urban administration and development");
						if (count(array_intersect($post_categories,$taxonomy_categories)) > 0):
							odm_get_template('post-grid-single-4-cols',array(
		  					"post" => $post,
		  					"show_meta" => true),true);
						endif;
					endwhile; ?>

      </div>

      <div class="four columns offset-by-one">
        <?php dynamic_sidebar('archive-sidebar'); ?>
      </div>

    </div>

	</section>

</div>


<?php get_footer(); ?>
