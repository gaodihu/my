

            <div class="guideline">
                <ul class="g-nav">
                <li class="on-blue"><a class="g-tit">Overview</a></li>
                <li><a  class="g-tit">Introduction</a></li>
                <li><a class="g-tit">Comparison</a></li>
                <li><a  class="g-tit">Advantages</a></li>
                <li><a  class="g-tit">Parameters</a></li>
                <li class="tabli">
                    <div class="white-side"></div>
                    <a href="applications.html" class="g-tit">Applications</a>
                     <div class="content-tab">
                       <?php foreach($all_app_info as $app_info){ ?>
                            <ul>
                                <li><b><a href="applications/c/<?php echo $app_info['url_path'];?>.html"><?php echo $app_info['catagory_name'];?></a></b></li>
                                <?php if($app_info['child']){ ?>
                                <?php foreach($app_info['child'] as $child){ ?>
                                <li><a href="applications/c/<?php echo $child['url_path'];?>.html"><?php echo $child['catagory_name'];?></a></li>
                                <?php } ?>
                                <?php } ?>
                            </ul>
                          <?php } ?>
                         
                     </div>
                </li>
                </ul>
            <!--  <div class="g-content">
                    <?php echo $information_info['content'];?>
                    
                
                </div>  -->
<div class="g-content guide-other">
<div>
<h4>Overview</h4>
<h5>1.Introduction</h5> 
<h5>2.Comparison</h5>
<h5>3.Advantages</h5>
<h5>4.Parameters</h5>
</div>                    
<h4>Introduction</h4>
<h5>What  is LED?</h5>

  <p>LEDs, or light-emitting diodes, are a type  of electronic light source. As a type of &quot;solid-state&quot; lighting, LEDs  are fundamentally different from the conventional light sources such as  incandescent, fluorescent and gas-discharge lamps. They consume far less  energy, last much longer and generate less heat. Moreover, they contain  no mercury or lead and as they don't feature any fragile parts such as glass  tubes or filaments, they are highly durable.</p>
<p>The first commercial LEDs were first  developed in the 1960s and were used as indicators in many electrical  appliances, such as televisions and computers. Since the development of  &quot;high-brightness&quot; blue LEDs in the 1990s, it has become possible to  produce LEDs with a clear, bright white light, making them suitable for general  purpose lighting.</p>
<p>Some of the earliest LED Light Bulbs  featured a cluster, or array, of LEDs which worked together to deliver the full  light output of the bulb. Since the introduction of high-power LEDs, it has  become possible to create LED Light Bulbs using fewer individual LEDs.</p>
<p>Now, LED Bulbs are available in some of the  most common light fittings, including mains voltage GU10, B22, E27 and  low-voltage (12V) MR16.</p>


<h4>Comparison</h4>
<h5>1.The comparison among incandescent light, CFL and LED light</h5>
<table border="1" cellspacing="0" cellpadding="0" width="720">
  <tr>
    <td width="187" colspan="2" valign="top"><p>&nbsp;</p></td>
    <td width="123" valign="top"><p align="center"><img src="images/guide/1.png" alt="" width="111" height="172" /><br />
      <strong>LED    Bulb</strong></p></td>
    <td width="180" valign="top"><p align="center"><img src="images/guide/2.png" alt="" width="99" height="172" /><br />
      <strong>CFL </strong></p></td>
    <td width="123" valign="top"><p align="center"><img src="images/guide/3.png" alt="" width="113" height="180" /><br />
      <strong>Incandescent    Bulb</strong></p></td>
  </tr>
  </tr>
  <tr>
    <td width="187" colspan="2" valign="top"><p class="p-left">Life Span</p></td>
    <td width="123" valign="top"><p>50,000 hours</p></td>
    <td width="180" valign="top"><p>8,000 hours</p></td>
    <td width="123" valign="top"><p>1,200 hours</p></td>
  </tr>
  <tr>
    <td width="187" colspan="2" valign="top"><p  class="p-left">Output/450LM</p></td>
    <td width="123" valign="top"><p>4-5W</p></td>
    <td width="180" valign="top"><p>9-13W</p></td>
    <td width="123" valign="top"><p>40W</p></td>
  </tr>
  <tr>
    <td width="187" colspan="2" valign="top"><p  class="p-left">Heat Emitted</p></td>
    <td width="123" valign="top"><p>3-4btu&rsquo;s/hour</p></td>
    <td width="180" valign="top"><p>30 btu&rsquo;s/hour</p></td>
    <td width="123" valign="top"><p>85 btu&rsquo;s/hour</p></td>
  </tr>
  <tr>
    <td width="83" rowspan="2" valign="center"><p  class="p-left">Environment</p></td>
    <td width="104" valign="top"><p  class="p-left">Carbon Dioxide Emissions</p></td>
    <td width="123" valign="top"><p>451pounds/year</p></td>
    <td width="180" valign="top"><p>1051 pounds/year</p></td>
    <td width="123" valign="top"><p>4500 pounds/year</p></td>
  </tr>
  <tr>
    <td width="104" valign="top"><p  class="p-left">Contains Toxic    Mercury</p></td>
    <td width="123" valign="top"><p>No</p></td>
    <td width="180" valign="top"><p  class="p-left">Yes, Mercury is    very toxic to your health and the environment</p></td>
    <td width="123" valign="top"><p>No</p></td>
  </tr>
  <tr>
    <td width="187" colspan="2" valign="top"><p  class="p-left">Operating Cost/year</p></td>
    <td width="123" valign="top" class="redbg"><p><b>$32.9/year</b></p></td>
    <td width="180" valign="top" class="redbg"><p><b>$76.7/year</b></p></td>
    <td width="123" valign="top" class="redbg"><p><b>$328.5/year</b></p></td>
  </tr>
</table>
<br/>

<h5  style="background:#FFCCFF;display:inline-block;padding:2px 5px;">2.The Difference between RC Driver and Constant Current Driver for LED Bulbs</h5>
<p>RC (resistance-capacitance) driver is just a simple LED driver mode. It actually through RC buck rectifies a 220V AC power to a certain DC voltage, which is a rough and unstable, less demanding driver. Also, RC drive will also harm a LED bulb, to make it decay quickly, even burned. So RC drive means the lower cost, but higher security risk.</p>
<p>The Constant Current Output Driver adopts the constant current mode. IC solution is to use the primary transformer inductance way. The line is simple and flexible. Depending on the load, it can have different output voltages. That means if the load changes, the output voltage will change accordingly, in order to ensure the constant current output. So this kind of LED bulb with IC Solution Constant Current Output Driver, could definitely insure the safety and stability of LED bulb in MyLED. </p>



<h4>Advantage</h4>
  <h5>1.High  efficiency </h5>
  <p>LEDs are efficiently-producing a lot of light  from a little power. For example, one 5-watt LED can produce more light  (measured in lumen) than one standard 75-watt incandescent bulb. The 5-watt  LED could do the job of the 75-watt incandescent at 1/15 of the energy  consumption. LED saves energy and, therefore, money. This is because in LED  lights, 90% of energy is converted into light, while in incandescent bulbs 90%  of energy goes to heat and only 10% to visible light. </p>
<h5>2.Long  service life </h5>
  <p>Compared with the incandescent light bulbs,  LEDs do not burn out since they do not have a filament that is easily burned out.  Instead they are gradually with longer lifespan when the LED technology and  science are improved. A standard &quot;long life&quot; household bulb will burn  for about 2,000 hours. An LED can have a useful lifespan up to 50,000 hours! By  some sources, LEDs can last for as long as 40 years. Imagine not having to  change a light bulb for years. Compared with the traditional incandescent light  and CFL, LED saves the maintenance fee and the troubles for replacing the bulbs  frequently.   <strong></strong></p>
<h5>3.Green  and health</h5>
 <p> LED belongs to cold light source which is  with small glare, no radiation, no use of harmful substances. LED uses the DC  drive, the operating voltage is low, and with ultra-low power consumption  (single tube 0.03 ~ 0.06W), electro-optical power conversion close to 100%,  under the same lighting effect, it saves over 80% energy than the traditional  sources. LED's environmental benefits are much better than the traditional bulbs;  LEDs do not emit ultraviolet and infrared in spectra. And the scraped LED light  material is recyclable, so there is no pollution and no mercury elements emits, which  is safe to touch, typical of the green lighting.</p>
<h5>4.Save </h5>
  <p>For the past years, LEDs were too  expensive to use for most lighting applications because of their advanced  semiconductor material. The price of semiconductor devices has fallen down in the  past decades; which has made the LEDs a more cost-effective lighting option  for a wide range of situations. It is estimated that replacing a single  traditional bulb with an LED light saves off $50 from your power bill. This  saving is greatly multiplied if you opt to use LED lights to light all rooms in  your home. Moreover, its low power consumption, long service life can save a  lot of money for long time use. <strong></strong></p>

  
  
  
  
<h4>Parameter</h4>
  <h5>1.luminous  flux</h5>
  <p>In photometry, luminous flux or luminous  power is the measure of the perceived power of light. Luminous flux is adjusted  to reflect the varying sensitivity of the human eye to different wavelengths of  light. <br />
  The SI unit of luminous flux is the lumen  (lm). One lumen is defined as the luminous flux of light produced by a light  source that emits one candela of luminous intensity over a solid angle of one  steradian. <br />
  The luminous flux accounts for the  sensitivity of the eye by weighting the power at each wavelength with the  luminosity function, which represents the eye's response to different  wavelengths. The luminous flux is a weighted sum of the power at all  wavelengths in the visible band. Light outside the visible band does not  contribute. <br />
  Luminous flux is often used as an objective  measure of the useful power emitted by a light source, and is typically  reported on the packaging for light bulbs, although it is not always prominent.  Energy conscious consumers commonly compare the luminous flux of different  light bulbs since it provides an estimate of the apparent amount of light the  bulb will produce, and is useful when comparing the luminous efficacy of  incandescent and compact fluorescent bulbs.<br />
  Please note that Luminous flux is not used  to compare brightness, as this is a subjective perception which varies  according to the distance from the light source.</p>

  <h5>2.Color temperature</h5>
  <p>The color temperature of a light source is  the temperature of an ideal black-body radiator that radiates light of  comparable hue to that of the light source. Color temperature is a  characteristic of visible light that has important applications in lighting,  photography, videography, publishing, manufacturing, astrophysics,  horticulture, and other fields. In practice, color temperature is only  meaningful for light sources that do in fact correspond somewhat closely to the  radiation of some black body, i.e. those on a line from reddish/orange via  yellow and more or less white to blueish white; it does not make sense to speak  of the color temperature of e.g. a green or a purple light. Color temperature  is conventionally stated in the unit of absolute temperature, the kelvin,  having the unit symbol K.<br />
  Color temperatures over 5,000K are called  cool colors (bluish white), while lower color temperatures (2,700–3,000 K) are  called warm colors (yellowish white through red). This relation, however, is a  psychological one in contrast to the physical relation implied by Wien's  displacement law, according to which the spectral peak is shifted towards  shorter wavelengths (resulting in a more blueish white) for higher  temperatures.<br />
  <img src="images/guide/untitled.png" alt="" width="558" height="407" /><br />
  <h5>3.Color  Rendering Index（CRI）</h5>
  <p>The color rendering index (CRI), sometimes  called color rendition index, is a quantitative measure of the ability of a  light source to reveal the colors of various objects faithfully in comparison  with an ideal or natural light source. Light sources with a high CRI are  desirable in color-critical applications such as photography and  cinematography. It is defined by the International Commission on Illumination  (CIE, in French) as follows:<br />
  <strong>Color  rendering</strong>: Effect of an illuminant on the color  appearance of objects by conscious or subconscious comparison with their color  appearance under a reference illuminant.</p>
<h5>4.IP </h5>
<table border="1" cellspacing="0" cellpadding="0" width="720" >
  <tr class="title-guide">
    <td width="26" valign="top">Number </td>
    <td width="302" valign="top">The first number—dustproof</td>
    <td width="283" valign="top">The second    number —waterproof</td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>0</p></td>
    <td width="302" valign="top"><p  class="p-left">No protection</p></td>
    <td width="283" valign="top"><p  class="p-left">No protection</p></td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>1</p></td>
    <td width="302" valign="top"><p  class="p-left">Protection against solid foreign    object &gt;50mm.&nbsp;</p></td>
    <td width="283" valign="top"><p  class="p-left">Protection against vertically    falling&nbsp;water.&nbsp;</p></td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>2</p></td>
    <td width="302" valign="top"><p class="p-left" >Protection against solid foreign    object&nbsp;&gt;12mm.&nbsp;</p></td>
    <td width="283" valign="top"><p  class="p-left">Protection against vertically falling    water when the enclosure is tilted up&nbsp;15 degrees.&nbsp;</p></td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>3</p></td>
    <td width="302" valign="top"><p  class="p-left">Protection against solid foreign    object &gt;2.5mm.&nbsp;</p></td>
    <td width="283" valign="top"><p  class="p-left">Protection against spraying water (tilted    60 degrees).&nbsp;</p></td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>4</p></td>
    <td width="302" valign="top"><p  class="p-left">Protection against solid foreign    object&nbsp;&gt;1mm.&nbsp;</p></td>
    <td width="283" valign="top"><p  class="p-left">Protection against splashing    water.&nbsp;</p></td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>5</p></td>
    <td width="302" valign="top"><p class="p-left" >Dust protected&nbsp;</p></td>
    <td width="283" valign="top"><p class="p-left" >Protection against water jet (12.5L/minute).</p></td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>6</p></td>
    <td width="302" valign="top"><p  class="p-left">Dust tight</p></td>
    <td width="283" valign="top"><p  class="p-left">Protection against powerful water jet (100L/minute).&nbsp;</p></td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>7</p></td>
    <td width="302" valign="top"><p class="p-left" >_____</p></td>
    <td width="283" valign="top"><p class="p-left" >Protection against temporary&nbsp;immersion    in water (30 minutes, 1m&nbsp;below surface).</p></td>
  </tr>
  <tr>
    <td width="26" valign="top"><p>8</p></td>
    <td width="302" valign="top"><p  class="p-left">_____</p></td>
    <td width="283" valign="top"><p  class="p-left">Protection against continuous immersion    in water (conditions to be defined by manufacturer and buyer).</p></td>
  </tr>
</table>



  
  <h5>5.Certification</h5>
  <p class="p-left">While consumers are learning more about LED  lighting, there are still many questions to be answered. Many LED products lack  minimum safety, environmental and performance standards, letting consumers  unsure about product reliability. Thankfully, there are a number of recognized  LED lighting certifications that consumers can rely upon. Let&rsquo;s take a look at  some of these certifications, and what they actually mean for consumers.</p>

<table border="1" cellspacing="0" cellpadding="0" width="720">
  <tr>
    <td width="113" rowspan="6"><p align="center"><strong>America Market</strong></p></td>
    <td width="140"><p align="center"><img src="images/guide/4.png" alt="" width="98" height="31" /></p></td>
    <td width="362" valign="top"><p  class="p-left"><strong>Occupational Safety and Health    Administration</strong> (<strong>OSHA</strong>): is an agency of the    United States Department of Labor. Congress established the agency under the Occupational    Safety and Health Act, which President Richard M.    Nixon signed into law on December 29, 1970.<br />
      Its mission&nbsp;is to prevent    work-related injuries, illnesses, and occupational fatality by issuing and    enforcing standards for workplace safety and health.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/5.png" alt="" width="74" height="74" /></p></td>
    <td width="362" valign="top"><p  class="p-left"><strong>UL    (Underwriters Laboratories)</strong>:is a safety consulting and certification    company headquartered in Northbrook, Illinois. It maintains offices in 46 countries. UL    was established in 1894 and has participated in the safety analysis of many    of the last century's new technologies, most notably the public adoption of    electricity and the drafting of safety standards for electrical devices and components.<br />
      UL provides    safety-related certification, validation, testing, inspection, auditing,    advising and training services to a wide range of clients, including    manufacturers, retailers, policymakers, regulators, service companies, and    consumers.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/6.png" alt="" width="70" height="60" /></p></td>
    <td width="362" valign="top"><p  class="p-left">The <strong>Federal Communications Commission</strong> (<strong>FCC</strong>): is an independent agency of the United States government,    created by Congressional statute to regulate interstate and international    communications by radio, television, wire, satellite, and cable in all 50    states, the District of Columbia and U.S. territories.<br />
      The FCC works towards six goals in the    areas of broadband, competition,    the spectrum, the media, public    safety and homeland security. The Commission is also in    the process of modernizing itself.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/7.png" alt="" width="91" height="82" /></p></td>
    <td width="362" valign="top"><p  class="p-left"><strong>TÜV</strong>s (short    for German: <strong>Technischer Überwachungs-Verein</strong>, English:    Technical Inspection Association): are German organizations that work to    validate the safety of products of all kinds to protect humans and the    environment against hazards. As independent consultants,    they examine plants, motor    vehicles, energy installations, amusement    rides, devices and products (e.g. consumer goods) which require    monitoring.<br />
      The many subsidiaries of the TÜVs can    also act as project developers for energy and traffic concepts, as problem solvers in environmental protection, and as certification bodies. Many of the TÜV organizations also provide certification for various    international standards, such as ISO9001:2008 (quality management system) and    ISO/TS16949 (automotive quality management system).</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/8.png" alt="" width="74" height="74" /></p></td>
    <td width="362" valign="top"><p  class="p-left">The <strong>Canadian Standards    Association (CSA):</strong>is    a not-for-profit standards    organization which develops standards in 57 areas. CSA publishes standards in print and    electronic form and provides training and advisory services. CSA is composed    of representatives from industry, government, and consumer groups.<br />
      CSA began as the Canadian Engineering    Standards Association (CESA) in 1919, federally chartered to create    standards.[1] During World War I, lack of interoperability between technical    resources led to frustration, injury, and death. Britain requested that    Canada form a standards committee.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/9.png" alt="" width="71" height="71" /></p></td>
    <td width="362" valign="top"><p  class="p-left">The<strong> Argentine Normalization and Certification Institute </strong>(Spanish: Instituto Argentino de Normalización y    Certificación, IRAM) is the International Organization for Standardization (ISO) member body for Argentina.<br />
      It was founded    on May 2, 1935 under the name of Instituto Argentino de Racionalización de    Materiales, and is since then known as IRAM, even though its name was changed    in 1996.</p></td>
  </tr>
  <tr>
    <td width="113" rowspan="6"><p align="center"><strong>European Market</strong></p></td>
    <td width="140"><p align="center"><img src="images/guide/10.png" alt="" width="79" height="57" /></p></td>
    <td width="362" valign="top"><p  class="p-left">The<strong> </strong>CE    mark, or formerly <strong>EC mark</strong>, is a    mandatory conformity marking for certain products sold within the European Economic    Area (EEA)    since 1985.[1] The CE marking is also found on products sold outside the  EEA that are manufactured in, or designed to be    sold in, the EEA. This makes the CE marking recognizable worldwide even to    people who are not familiar with the European Economic Area. It is in that    sense similar to the FCC Declaration of    Conformity used on certain electronic devices sold in the United States.<br />
      It consists of    the CE logo and, if applicable, the four digit identification number of the    notified body involved in the conformity assessment procedure.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/11.png" alt="" width="81" height="82" /></p></td>
    <td width="362" valign="top"><p  class="p-left"><strong>Nordic    Certification</strong><strong>：(Nemko,&nbsp;Semko,&nbsp;Fimko,&nbsp;Demko)</strong><br />
      The Nordic certification service is a    long-standing agreement between the EMKO bodies:<br />
      <strong>Nemko</strong> as the national certification body in Norway<br />
      <strong>Demko (UL Int.)</strong> as the national certification body in    Denmark<br />
      <strong>Semko</strong> (Intertek ) as the national certification    body in Sweden<br />
      <strong>Fimko (SGS) </strong>as the national certification body in Finland<br />
      It implies that    the four marks (D)(FI)(N)(S) may be obtained by one single application and    submittal. Due to the well-known tradition for safety awareness in the Nordic    countries, these marks on a product carry a strong message of safety.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/12.png" width="92" height="74" /></p></td>
    <td width="362" valign="top"><p  class="p-left">The <strong>Geprüfte    Sicherheit</strong> (&quot;Tested Safety&quot;) or <strong>GS mark</strong> is a voluntary certification mark for technical equipment. It indicates that    the equipment meets German and, if available, European safety    requirements for such devices. The main difference between GS and CE mark is that the compliance with the European    safety requirements has been tested and certified by a state-approved    independent body. CE marking, in contrast, is issued for the signing of a    declaration that the product is in compliance with European legislation. The    GS mark is based on the German Equipment and Product Safety Act    (&quot;Geräte- und Produktsicherheitsgesetz&quot;, or &quot;GPSG&quot;).<br />
      Testing for the mark is available from many    different laboratories, such as, DGUV Test the TÜV, Nemko and IMQ.<br />
      Although the GS    mark was designed with the German market in mind, it appears on a large    proportion of electronic products and machinery sold elsewhere in the world.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/13.png" alt="" width="93" height="76" /></p></td>
    <td width="362" valign="top"><p  class="p-left">To ensure that screwdrivers are safe to    use when working with electricity, standards are set for testing screwdrivers    and other hand tools and this standard is known as <strong>VDE</strong>. VDE takes its acronym from the company <strong><em>Verband der Elektrotechnik (Origionally - Association of German    Electrical Engineers, now - </em>Association for Electrical, Electronic    &amp;Information Technologies<em>)</em></strong>in Germany who is responsible for    testing and certifying tools and appliances.<br />
      VDE certification is a conformance of    assurance that certain electronic products have met the standards required by    the VDE institute. VDE marking is a condition that must be met for the    initial marketing of new products in the European Economic Area.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/14.png" alt="" width="85" height="49" /></p></td>
    <td width="362" valign="top"><p  class="p-left"><strong>KEMA</strong><strong> (Keuring van Elektrotechnische Materialen te    Arnhem)</strong> NV, established in 1927, is a global energy consultancy company headquartered    in Arnhem, Netherlands.    It offers management consulting, technology consulting &amp; services to the energy value chain that include business and technical consultancy, operational support,    measurements &amp; inspection, and testing &amp; certification services.</p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/15.png" alt="" width="83" height="65" /></p>
    <p align="center"></p></td>
    <td width="362" valign="top"><p  class="p-left"><strong>GOST</strong> (Russian: ГОСТ) refers to a set of technical standards maintained by the Euro-Asian Council for Standardization, Metrology and    Certification (EASC), a regional standards organization operating under    the auspices of the Commonwealth of Independent States    (CIS).<br />
      All sorts of regulated standards are    included, with examples ranging from charting rules for design documentation    to recipes and nutritional facts of Soviet-era brand names (which have now    become generic, but may only be sold under the label if the technical standard    is followed, or renamed if they are reformulated).The notion of GOST has    certain significance and recognition in the countries of the standards'    jurisdiction. Russian Rosstandart government agency has gost.ru as website address.</p></td>
  </tr>
  <tr>
    <td width="113" rowspan="4"><p align="center">&nbsp;</p>
      <p align="center"><b>Asian-Pacific&nbsp;and other    Market</b></p></td>
    <td width="140"><p align="center"><img src="images/guide/16.png" alt="" width="85" height="72" /></p></td>
    <td width="362" valign="top"><p  class="p-left">The<strong> China Compulsory Certificate</strong> mark,<strong> </strong>commonly known as CCC Mark, is a compulsory safety mark for many products    imported, sold or used in the Chinese market. It became implemented on May 1, 2002 and    fully effective on August 1, 2003.<br />
      It is the result of the integration of    China's two previous compulsory inspection systems, namely &quot;CCIB&quot;    (Safety Mark, introduced in 1989 and required for products in 47 product    categories) and &quot;CCEE&quot; (also known as &quot;Great Wall&quot; Mark,    for electrical commodities in 7 product categories), into a single procedure.<strong></strong></p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/17.png" alt="" width="102" height="81" /></p></td>
    <td width="362" valign="top"><p  class="p-left"><strong>Japan</strong><strong> PSE</strong> Mark Scheme regulates 453 electrical/ electronic appliances and    components according to Japan Denan Law.&nbsp; PSE Products consist of two    groups.&nbsp; Specified Products (SP, Category A) –including    DC power supplies(AC Adaptors, LED drivers), a total of 115 items and Non-Specified Products (NSP, Category B) –    338 items.&nbsp; All products should undergo product certification and    factory inspection by CAB (Certification accreditation body) recognized by    METI.&nbsp; Factory inspection is conducted by factory and product    category.&nbsp; The validity of certificate varies from 3~7 years according    to each product category.<strong></strong></p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/18.png" alt="" width="102" height="58" /></p></td>
    <td width="362" valign="top"><p  class="p-left"><strong>Standards    Australia</strong>: is a standard organisation established in 1922 and is recognized    through a Memorandum of Understanding with the Australian government as the peak non-government standards    development body in Australia.    It is a company limited by    guarantee, with    73 members representing groups interested in the development and application    of technical standards and related products and services. The    Memorandum of Understanding between the Commonwealth and Standards Australia    recognizes Standards Australia as Australia&rsquo;s representative on the International Organization for Standardization (ISO), the International    Electrotechnical Commission (IEC) and the Pacific Area Standards Congress    (PASC).<strong> </strong></p></td>
  </tr>
  <tr>
    <td width="140"><p align="center"><img src="images/guide/19.png" alt="" width="91" height="91" /></p></td>
    <td width="362" valign="top"><p  class="p-left">The <strong>Restriction    of Hazardous Substances Directive</strong> 2002/95/EC, RoHS, short for Directive on the restriction of the    use of certain hazardous substances in electrical and electronic equipment,    was adopted in February 2003 by the European    Union.<br />
      The RoHS directive took effect on 1 July 2006,    and is required to be enforced and become law in each member state. This    directive restricts (with exceptions)    the use of six hazardous materials in the manufacture of various types of    electronic and electrical equipment. It is closely linked with the Waste    Electrical and Electronic Equipment Directive (WEEE) 2002/96/EC    which sets collection, recycling and recovery targets for electrical goods    and is part of a legislative initiative to solve the problem of huge amounts    of toxic e-waste.<strong></strong></p></td>
  </tr>
</table>
<h5>6.Connectors</h5>
  <p>For those who are new to LED lamps, there  might be some sense of strange to classify LED lamp fixtures connectors. Here  I collected the most common used LED lamp connector, combined and organize with  the graphic papers information, we want to help. </p>
<p>LED lamp fixtures, as an integral part, is in  accordance with international standards. Common lamp connectors are divided into  Edison Screw, Bayonet Types, BI PIN, Fluorescent PIN, each interface is further  subdivided according to their size. Please refer to the below picture.</p>
<p><strong>(1) Edison  Screw</strong> (generally used in North America and  continental Europe)<strong></strong><br />
  Edison screw (ES) is a standard mount for  light bulbs (lamps). It was developed by Thomas Edison and was licensed in 1909  under the Mazda trademark. Normally, the bulbs have clockwise threaded metal  bases (caps) which screw into matching threaded sockets (lamp holders). For  bulbs powered by the mains supply, the thread is connected to neutral and the  contact on the bottom tip of the base is connected to live.<strong></strong></p>
<table border="1" cellspacing="0" cellpadding="0">
  <tr class="title-guide">
    <td width="110" valign="top">
      <p align="center"><strong>Designation</strong></p></td>
    <td width="110" valign="top"><p align="center"><strong>images/guide</strong></p></td>
    <td width="110" valign="top"><p align="center"><strong>Base diameter</strong></p></td>
    <td width="130" valign="top"><p align="center"><strong>Name</strong></p></td>
    <td width="250" nowrap="nowrap" valign="top"><p align="center"><strong>IEC 60061-1 standard sheet</strong></p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E5</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/01.png" alt="" width="52" height="60" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >5&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p class="p-left">Lilliput Edison Screw (LES)</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-25</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E10</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/02.png" alt="" width="45" height="60" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >10&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">Miniature Edison Screw (MES)</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-22</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E11</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/03.png" alt="" width="39" height="56" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >11&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">Mini-Candelabra Edison Screw (mini-can)</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >(7004-6-1)</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E12</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/04.png" alt="" width="45" height="58" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >12&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">Candelabra Edison Screw (CES), C7</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-28</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E14</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/05.png" alt="" width="50" height="67" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >14&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">Small Edison Screw (SES)</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-23</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E17</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/06.png" alt="" width="52" height="55" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >17&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">Intermediate Edison Screw (IES), C9</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-26</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E26</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/07.png" alt="" width="60" height="64" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >26&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">[Medium] (one-inch) Edison Screw (ES or MES), standard US bulb</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-21A-2</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E27</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/08.png" alt="" width="59" height="62" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >27&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">[Medium] Edison Screw (ES)</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-21</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E29</p></td>
    <td width="110" nowrap="nowrap" valign="top"></td>
    <td width="110" nowrap="nowrap" valign="top"><p >29&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">[Ad medium] Edison Screw (ES)</p></td>
    <td width="110" nowrap="nowrap" valign="top"></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E39</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/011.png" alt="" width="67" height="66" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >39&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">Single-contact (Mogul- in America) Goliath Edison Screw (GES)</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-24-A1</p></td>
  </tr>
  <tr>
    <td width="110" nowrap="nowrap" valign="top"><p >E40</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p ><img src="images/guide/012.png" alt="" width="64" height="69" /></p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >40&nbsp;mm</p></td>
    <td width="130" nowrap="nowrap" valign="top"><p  class="p-left">(Mogul) Goliath Edison Screw (GES)</p></td>
    <td width="110" nowrap="nowrap" valign="top"><p >7004-24</p></td>
  </tr>
</table>
<p><strong>(2)Bayonet  Types</strong> (generally used in the United Kingdom,  Australia, India, Sri Lanka, Ireland, New Zealand, parts of the Middle East and  Africa)<br />
  A bayonet connector (for electrical use) is  a fastening mechanism consisting of a cylindrical male side with one or more  radial pins, and a female receptor with matching L-shaped slot(s) and with  spring(s) to keep the two parts locked together. The slots are shaped like a  capital letter L with serif (a short upward segment at the end of the  horizontal arm); the pin slides into the vertical arm of the L, rotates across  the horizontal arm, then is pushed slightly upwards into the short vertical  &quot;serif&quot; by the spring; the connector is no longer free to rotate  unless pushed down against the spring until the pin is out of the  &quot;serif&quot;.</p>
<table border="1" cellspacing="0" cellpadding="0" width="574">
  <tr>
    <td width="158" align="center" valign="top"><img src="images/guide/015.png" alt="" width="79" height="82" /><br />
      <br />
      GU10 </td>
    <td width="416" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="158" valign="top"><p align="center"><img src="images/guide/016.png" alt="" width="92" height="83" /><br />
      GU24</p></td>
    <td width="416" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="158" valign="top"><p align="center">Double <a name="OLE_LINK12" id="OLE_LINK12"></a>Contact    Bayonet</p>
      <p align="center"><img src="images/guide/017.png" alt="" width="43" height="48" /><br />
      BA15d</p></td>
    <td width="416" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="158" valign="top"><p align="center"><a name="OLE_LINK14" id="OLE_LINK14"></a>Single    Contact Bayonet </p>
      <p align="center"><img src="images/guide/018.png" alt="" width="42" height="47" /><br />
      BA15s</p></td>
    <td width="416" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="158" valign="top"><p align="center">Single    Contact </p>
      <p align="center"><img src="images/guide/019.png" alt="" width="67" height="54" /><br />
      SC</p></td>
    <td width="416" valign="top"><p>&nbsp;</p></td>
  </tr>
</table>
<p><strong>(3) BI  PIN</strong> <br />
  A bi pin or bi-pin, (sometimes referred to  as 2-pin, bi pin cap or bi pin socket), is a standard from the IEC for lamp  fittings. These are used on many small incandescent light bulbs (especially  halogen lamps), and for starters on some types of fluorescent lights as well.<br />
  Some lamps have pins placed more closely  together, preventing them from being interchanged with bulbs that have larger circuit, which may cause excessive heat and possibly fire. These are sometimes  called &quot;mini-bi pin&quot;. Some of these, particularly in automotive  lighting, have the pins bent back onto the sides of the base of the bulb; this  is instead called a wedge base or socket.<strong></strong></p>
<table border="1" cellspacing="0" cellpadding="0" width="574">
  <tr>
    <td width="149" valign="top"><br />
      <img src="images/guide/001.png" alt="" width="44" height="90" /><br />
      G4 </td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/002.png" alt="" width="47" height="80" /><br />
      GU4</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/003.png" alt="" width="48" height="76" /><br />
      GU5.3</p></td>
    <td width="425" valign="top"><p class="p-left">The GU-5.3 is an MR-16 type lamp base. It    has two pins. MR-16's are found in track lighting heads, bath bars, some    ceiling fans.</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/004.png" alt="" width="59" height="92" /><br />
      GY6.35</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/005.png" alt="" width="54" height="77" /><br />
      GU8</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/006.png" alt="" width="46" height="86" /><br />
      GY8</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/007.png" alt="" width="52" height="84" /><br />
      GY8.6</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/008.png" alt="" width="55" height="104" /><br />
      G9</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/009.png" alt="" width="70" height="93" /><br />
      G12</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/0010.png" alt="" width="58" height="56" /><br />
      G4</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/0011.png" alt="" width="68" height="63" /><br />
      G5.3</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
</table>

<p><strong>(4)Fluorescent  PIN</strong></p>
<table border="1" cellspacing="0" cellpadding="0" width="574">
  <tr>
    <td width="149" valign="top"><br />
      <img src="images/guide/0012.png" alt="" width="65" height="53" /><br />
      Mini BI PIN</td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/0013.png" alt="" width="62" height="62" /><br />
      Medium BI PIN</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td width="149" valign="top"><p><img src="images/guide/0014.png" alt="" width="63" height="60" /><br />
      Single BI PIN</p></td>
    <td width="425" valign="top"><p>&nbsp;</p></td>
  </tr>
</table>
<h5>7.Body shapes</h5>
  <p>Light bulbs and lighting lamps are  described by designated shape names and diameter/length codes. This usually  takes a letter-number-letter format, though the last letter is optional. The  letter(s) indicates the shape.</p>
<table border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="149"><p align="center"><img src="images/guide/31.png" alt="" width="66" height="100" /><br />
      Arbitrary</p></td>
    <td width="230"><p align="center"><img src="images/guide/32.png" alt="" width="56" height="104" /><br />
      Bulged</p></td>
    <td width="189"><p align="center"><img src="images/guide/33.png" alt="" width="56" height="98" /><br />
      Blown    Tubular</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/34.png" alt="" width="84" height="109" /><br />
      Bulged    Reflector</p></td>
    <td width="230"><p align="center"><img src="images/guide/35.png" alt="" width="44" height="91" /><br />
      Candle</p></td>
    <td width="189"><p align="center"><img src="images/guide/36.png" alt="" width="45" height="106" /><br />
      Candle    Angular</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/37.png" alt="" width="41" height="89" /><br />
      Candle    Twisted</p></td>
    <td width="230"><p align="center"><img src="images/guide/38.png" alt="" width="67" height="93" /><br />
      Crystalline    Pear</p></td>
    <td width="189"><p align="center"><img src="images/guide/39.png" alt="" width="44" height="115" /><br />
      Extended    Candle</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/40.png" alt="" width="62" height="103" /><br />
      Ellipsoidal</p></td>
    <td width="230"><p align="center"><img src="images/guide/41.png" alt="" width="58" height="107" /><br />
      Ellipsoidal    Dimple</p></td>
    <td width="189"><p align="center"><img src="images/guide/42.png" alt="" width="63" height="106" /><br />
      Ellipsoidal    Reflector</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/45.png" alt="" width="52" height="91" /><br />
      Flambeau</p></td>
    <td width="230"><p align="center"><img src="images/guide/46.png" alt="" width="83" height="93" /><br />
      Globe</p></td>
    <td width="189"><p align="center"><img src="images/guide/47.png" alt="" width="72" height="91" /><br />
      Decorator</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/48.png" alt="" width="66" height="90" /><br />
      Krypton</p></td>
    <td width="230"><p align="center"><img src="images/guide/49.png" alt="" width="68" height="101" /><br />
      Pear</p></td>
    <td width="189"><p align="center"><img src="images/guide/50.png" alt="" width="55" height="112" /><br />
      Hexagonal    Candle</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/51.png" alt="" width="73" height="106" /><br />
      Ogive</p></td>
    <td width="230"><p align="center"><img src="images/guide/52.png" alt="" width="71" height="110" /><br />
      Pear-Straight</p></td>
    <td width="189"><p align="center"><img src="images/guide/54.png" alt="" width="102" height="100" /><br />
      Sealed    Beam Parabolic Anodized Reflector</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/55.png" alt="" width="54" height="87" /><br/>Straight-Sided</p></td>
    <td width="230"><p align="center"><img src="images/guide/56.png" alt="" width="69" height="116" /><br />
      Straight-Tubular</p></td>
    <td width="189"><p align="center"><img src="images/guide/57.png" alt="" width="69" height="97" /><br />
      Tubular</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/58.png" alt="" width="43" height="112" /><br />
      Tubular </p></td>
    <td width="230"><p align="center"><img src="images/guide/59.png" alt="" width="44" height="117" /><br />
      Tubular    Angular </p></td>
    <td width="189"><p align="center"><img src="images/guide/60.png" alt="" width="76" height="69" /><br />
      Multifaced Reflector</p></td>
  </tr>
  <tr>
    <td width="149"><p align="center"><img src="images/guide/61.png" alt="" width="69" height="92" /><br />
      Blown Reflector</p></td>
    <td width="230"><p align="center"><img src="images/guide/62.png" alt="" width="71" height="93" /><br />
      Double Reflector</p></td>
    <td width="189"><p align="center">&nbsp;</p></td>
  </tr>
</table>

<h5>8. World Plugs and Sockets</h5>

<p class="bold m_t10">Type A</p>
<p><img src="images/plugs/image001.jpg" class="img400" /></p>
 
<p>Used in: North and Central America, Japan<br/>
The Type A electrical plug has two-prong plug and socket design with two flat parallel non-coplanar blades and slots. The Japanese plug has two identical flat prongs, whereas the US plug has one prong which is slightly larger. As a result, Japanese plugs can be used in the US but often not the other way around. </p>

<p class="bold m_t10">Type B</p>
 <p><img src="images/plugs/image002.jpg" class="img400" /></p>
<p>Used in: North and Central America, Japan<br/>
The Type B plug has two flat parallel blades like Type A, but also adds a ground (earth) pin. It is rated 15 A at 125 volts. The ground (earth) pin is longer than the line and neutral blades, so the device is grounded before the power is connected. Both current-carrying blades on grounding plugs are normally narrow, since the ground (earth) pin enforces polarity. As with the type A plugs, the American and Japanese versions vary slightly.</p>

<p class="bold m_t10">Type C</p>
 <p><img src="images/plugs/image003.jpg" class="img400" /></p>
<p>Used in: Europe, with the exception of the UK, Ireland, Cyprus and Malta<br/>
The Type C is ungrounded and has 2 round prongs. It will mate with any outlet that accepts 4.0 – 4.8mm round contacts on 19mm centers. The plug is generally limited for use in applications that require 2.5 amps or less. Nowadays most countries demand grounded outlets to be installed in new buildings. Since type C outlets are ungrounded, they are currently being phased out in many countries and replaced by type E, F, J, K or L which work perfectly with Type C plugs.  </p>

<p class="bold m_t10">Type D</p>
<p><img src="images/plugs/image004.jpg" class="img400" /></p>
 
<p>Used in: India, Sri Lanka, Nepal, Namibia<br/>
The Type D has 3 large round pins in a triangular pattern. It is rated at 5 amps. Type M has larger pins and is rated at 15 amps, is used alongside type D for larger appliances in India, Sri Lanka, Nepal and Namibia. Some outlets can take both type M and type D plugs. </p>

<p class="bold m_t10">Type E</p>
 <p><img src="images/plugs/image005.jpg" class="img400"  /></p>
<p>Used in: France, Belgium, Slovakia and Tunisia among others<br/>
The Type E electrical plug has two 4.8 mm round pins spaced 19 mm apart and a hole for the socket’s male grounding pin. The socket has two symmetrical round apertures and a round 4.8 mm (0.189 in) earth pin projecting from the socket such that the tip is 23 mm (0.906 in) beyond the live contacts, to ensure that the earth is always engaged before live pin contact is made. </p>

<p class="bold m_t10">Type F</p>
 <p><img src="images/plugs/image006.jpg" class="img400" /></p>
<p>Used in: Germany, Austria, the Netherlands and Spain among others<br/>
The Type F is commonly called the Schuko plug, which is the acronym of “Schutzkontakt”, a German word meaning “earthed/grounded contact”. The socket has two symmetrical round apertures and two earthing clips on the sides of the socket positioned to ensure that the earth is always engaged before live pin contact is made. The plug pins are 4.8 by 19 mm. It is similar to the Type E plug but has two earth clips on the side rather than a female earth contact. </p>

<p class="bold m_t10">Type G</p>
 <p><img src="images/plugs/image007.jpg" class="img400" /></p>
<p>Used in: UK, Ireland, Cyprus, Malta, Malaysia, Singapore, Hong Kong<br/>
The Type G electrical plug has three rectangular pins forming an isosceles triangle. As the type G socket is conventionally used with ring circuits the plug has a fuse to protect the appliance flexible cable from overload.  </p>

<p class="bold m_t10">Type H</p>
  <p><img src="images/plugs/image008.jpg" class="img400" /></p>
<p>Used in: Israel<br/>
The Type H plug is unique to Israel and has two flat pins in a V-shape as well as a grounding pin and is rated at 16 amps. There are two versions: an older one with flat pins, and a newer one with round pins. The holes in Type H sockets are wide in the middle so as to accommodate the round-pinned version of the Type H plug as well as Type C plugs.  </p>

<p class="bold m_t10">Type I</p>
  <p><img src="images/plugs/image009.jpg" class="img400" /></p>
<p>Used in: Australia, New Zealand, Fiji, Tonga, Solomon Islands, and Papua New Guinea<br/>
The Type I plug has a grounding pin and two flat current-carrying pins forming an upside down V-shape. The flat blades measure 6.5 by 1.6 mm (0.256 by 0.063 in) and are set at 30° to the vertical at a nominal pitch of 13.7 mm (0.539 in).  </p>

<p class="bold m_t10">Type J</p>
  <p><img src="images/plugs/image010.jpg" class="img400" /></p>
<p>Used in: Switzerland and Lichtenstein<br/>
The Type J plug has two round pins as well as a grounding pin and is rated 10 amps. This plug is similar to type C, except that it has the addition of a grounding pin. A type C plug works in a type J outlet. </p>

<p class="bold m_t10">Type K</p>
  <p><img src="images/plugs/image011.jpg" class="img400" /></p>
<p>Used in: Denmark and Greenland<br/>
The Type K plug has two round pins as well as a grounding pin. This plug is similar to type F except that it has a grounding pin instead of grounding clips. Because of the huge amount of E/F plugs in Denmark, the Danish government decided to make it legal to install type E instead of type K outlets from 2008 onwards. A type C plug fits perfectly into a type K outlet.  </p>

<p class="bold m_t10">Type L</p>
  <p><img src="images/plugs/image012.jpg" class="img400" /></p>
<p>Used in: Italy<br/>
The Italian grounded plug/outlet standard includes 2 styles rated at 10 and 16 amps. They differ in terms of contact diameter and spacing, and are therefore incompatible with each other. The 10 amp version has two round pins that are 4 mm think and spaced 5.5 mm apart, with a grounding pin in the middle. The 16 amp version has two round pins that are 5 mm thick, spaced 8mm apart, as well as a grounding pin. Italy has a kind of “universal” socket that comprises a “schuko” socket for C, E, F and L plugs and a “bipasso” socket for L and C plugs. </p>

<p class="bold m_t10">Type M</p>
  <p><img src="images/plugs/image013.jpg" class="img400" /></p>
<p>Used in: South Africa, Swaziland, Lesotho<br/>
The Type M plug has three round pins in a triangular pattern and looks similar to the Indian Type D plug, but its pins are much larger. Type M is rated at 15 amps. Although type D is standard in India, Sri Lanka, Nepal and Namibia, type M is also used for larger appliances. </p>

<p class="bold m_t10">Type N</p>
  <p><img src="images/plugs/image014.jpg" class="img400" /></p>
<p>Used in: Brazil <br/>
There are two variations of the Type N plug, one rated at 10 amps, and one at 20 amps. The 10 amp version has two round pins that are 4 mm thick, and a grounding pin. The 20 amp version, used for heavier appliances, has two round pins 4.8 mm in diameter, and a grounding pin. The Type N socket was designed to work with Type C plugs as well.</p>

<p>Brazil is one of the few countries that use two types of voltage. While most states use 127 V, some of them use 220 V. It is therefore important to find out the local voltage before plugging in your appliance (note: wrong voltage can destroy your appliance). Many appliances sold in Brazil are dual voltage.</p>



<h5>9.Voltage</h5>
<p>Various regions of the world, national  standard voltage and frequency will be different, so when choose LED fixtures, you must select  the product voltage consistent with the  area standard voltage, and the following table is voltage and frequency corresponding to  each region for  reference.</p>
<table cellspacing="0" cellpadding="5" border="1" width="574">
  <col width="289">
  <col width="111">
  <col width="96">
  <tr  class="title-guide">
    <td width="289"><div align="left">Location</div></td>
    <td width="111">Voltage</td>
    <td width="96">Frequency</td>
  </tr>
  <tr>
    <td><div align="left">Afghanistan</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Albania</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Algeria</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">American Samoa</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Andorra</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Angola</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Anguilla</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Antigua and    Barbuda</div></td>
    <td>230V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Argentina</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Armenia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Aruba</div></td>
    <td>127V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Australia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Austria</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Azerbaijan</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Bahamas</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Bahrain</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Bangladesh</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Barbados</div></td>
    <td>115V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Belarus</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Belgium</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Belize</div></td>
    <td>110V,220V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Benin</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Bermuda</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Bhutan</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Bolivia</div></td>
    <td>115V,230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Bosnia and    Herzegovina</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Botswana</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Brazil</div></td>
    <td>127V,220V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">British Virgin    Islands</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Brunei    Darussalam</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Bulgaria</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Burkina Faso</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Burundi</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Cambodia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Cameroon</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Canada</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Cape Verde</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Cayman Islands</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Central    African Republic</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Chad</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Chile</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">China</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Colombia</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Comoros</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Congo</div></td>
    <td>220V,230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Cook Islands</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Costa Rica</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Cote d'Ivoire</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Croatia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Cuba</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Cyprus</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Czech Republic</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Denmark</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Djibouti</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Dominica</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Dominican    Republic</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Ecuador</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Egypt</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">El Salvador</div></td>
    <td>115V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Equatorial    Guinea</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Eritrea</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Estonia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Ethiopia</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Falkland    Islands (Malvinas)</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Faroe Islands</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Fiji</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Finland</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">France</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">French Guiana</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Gabon</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Gambia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Georgia</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Germany</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Ghana</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Gibraltar</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Greece</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Greenland</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Grenada</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Guadeloupe</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Guam</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Guatemala</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Guinea</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Guinea Bissau</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Guyana</div></td>
    <td>240V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Haiti</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Honduras</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Hong Kong</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Hungary</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Iceland</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">India</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Indonesia</div></td>
    <td>110V,220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Iran</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Iraq</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Ireland</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Isle of Man</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Israel</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Italy</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Jamaica</div></td>
    <td>110V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Japan</div></td>
    <td>100V</td>
    <td>50Hz, 60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Jordan</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Kazakhstan</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Kenya</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Kiribati</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Korea</div></td>
    <td>110V,220V</td>
    <td>50Hz, 60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Kuwait</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Kyrgyzstan</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Lao People's    Democratic Republic</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Latvia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Lebanon</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Lesotho</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Liberia</div></td>
    <td>120V,220V</td>
    <td>50Hz,60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Libya</div></td>
    <td>127V,230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Liechtenstein</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Lithuania</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Luxembourg</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Macau</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Macedonia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Madagascar</div></td>
    <td>127V,220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Malawi</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Malaysia</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Maldives</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Mali</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Malta</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Martinique</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Mauritania</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Mauritius</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Mexico</div></td>
    <td>127V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Micronesia</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Moldova</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Monaco</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Mongolia</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Montenegro</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Montserrat</div></td>
    <td>230V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Morocco</div></td>
    <td>127V,220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Mozambique</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Myanmar</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Namibia</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Nauru</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Nepal</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Netherlands</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Netherlands    Antilles</div></td>
    <td>127V,220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">New Caledonia</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">New Zealand</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Nicaragua</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Niger</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Nigeria</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">North Korea</div></td>
    <td>220V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Norway</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Oman</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Pakistan</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Palau</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Panama</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Papua New    Guinea</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Paraguay</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Peru</div></td>
    <td>220V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Philippines</div></td>
    <td>220V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Poland</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Portugal</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Puerto Rico</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Qatar</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Reunion</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Romania</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Russia</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Rwanda</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Saint Kitts    and Nevis</div></td>
    <td>230V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Saint Lucia</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Saint Martin</div></td>
    <td>120V,220V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Saint Vincent    and the Grenadines</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Samoa</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">San Marino</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Sao Tome and    Principe</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Saudi Arabia</div></td>
    <td>127V,220V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Senegal</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Serbia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Seychelles</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Sierra Leone</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Singapore</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Slovakia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Slovenia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Solomon    Islands</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Somalia</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">South Africa</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Spain</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Sri Lanka</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Sudan</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Suriname</div></td>
    <td>127V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Swaziland</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Sweden</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Switzerland</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Syrian Arab    Republic</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Taiwan</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Tajikistan</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Tanzania</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Thailand</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Timor-Leste</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Togo</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Tonga</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Trinidad and    Tobago</div></td>
    <td>115V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Tunisia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Turkey</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Turkmenistan</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Turks and    Caicos Islands</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Tuvalu</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Uganda</div></td>
    <td>240V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Ukraine</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">United Arab    Emirates</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">United Kingdom</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">United States    of America</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">United States    Virgin Islands</div></td>
    <td>110V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Uruguay</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Uzbekistan</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Vanuatu</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Venezuela</div></td>
    <td>120V</td>
    <td>60Hz</td>
  </tr>
  <tr>
    <td><div align="left">Vietnam</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Yemen</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Zambia</div></td>
    <td>230V</td>
    <td>50Hz</td>
  </tr>
  <tr>
    <td><div align="left">Zimbabwe</div></td>
    <td>220V</td>
    <td>50Hz</td>
  </tr>
</table>
</div>

            </div>
    </div>
<script type="text/javascript">

    $(function(){

        //滚动锚点
        $(".g-nav li").click(function(){
            if($(this).hasClass("tabli")){
                return;
            }
            var index = $(this).index();
            console.log(index);
            $(this).addClass("on-blue").siblings("li").removeClass("on-blue");
            $("html,body").animate({scrollTop:$(".g-content h4").eq(index).offset().top},1000);

        });
        $(".tabli").hover(function(){
            $(this).addClass("active-tab");
            $(".content-tab").show();
        },function(){
            $(this).removeClass("active-tab");
            $(".content-tab").hide();
        })
    });


        $(window).scroll(function() {
			// 滚动加样式
            //console.log($(".g-content h4").eq(2).offset().top);
			//console.log(scroll);
			
            var scroll = $(window).scrollTop();
			$(".g-content h4").each(function(index){	
				if(scroll+100 > $(".g-content h4").eq(index).offset().top){
					$(".g-nav li").eq(index).addClass("on-blue").siblings("li").removeClass("on-blue");	
					//console.log(scroll);
				}
										
			})
			// 判断定位
            if(scroll >= 150){
                $(".g-nav").css({"position":"fixed","top":"5px"});
            }
            if(scroll <= 150){
                $(".g-nav").css({"position":"static"});
            }
        });


</script>