<?php

/* Copyright (C) 2004 Alexandre Courbot. <alexandrecourbot@linuxgames.com>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

See the COPYING file for more details. */

/*
 * Based on BibtexRef plugin for PmWiki, https://www.pmwiki.org/wiki/Cookbook/BibtexRef
 * Modified to generate Markdown - Michele Weigle, July 2021 
 * Modified to keep {} in output of raw BibTeX - Michele Weigle, February 2022
*/

$BibtexPdfLink = "PDF";    // removed parens -MCW
//$BibtexPdfLink = "Attach:pdf.gif";  // uses Acrobat icon
$BibtexDoiLink = "DOI";    // added -MCW
$BibtexUrlLink = "URL";    // removed parens -MCW 04/21/08
$BibtexBibLink = "BibTeX";
$BibtexPreprintLink = "preprint"; // added link to preprint -MCW
$BibtexSlidesLink = "slides"; // added link to slides -MCW
$BibtexPosterLink = "poster"; // added link to poster -MCW
$BibtexArxivLink = "arXiv";  // added link to arXiv.org version -MCW 12/18/13     
$BibtexTripReportLink = "trip report"; // added link to trip report -MCW 5/28/14

/* Markdown buttons/icons */
$BibtexLinkIcon = "<i class='fas fa-fw fa-link'></i>";
$BibtexDoiIcon = "<i class='ai ai-fw ai-doi' style='color: {{ page.doi-color }}'></i>";
$BibtexPdfIcon = "<i class='fas fa-solid fa-file-pdf' style='color: {{ page.acrobat-color }}'></i>";
$BibtexTripIcon = "<i class='fab fa-blogger' style='color: {{ page.blogger-color }}'></i>";
$BibtexSlideshareButton = "class='btn btn--mcwslideshare'><img src='../images/slideshare-16px-high.png'/>";
$BibtexArxivButton = "class='btn btn--mcwarxiv'><img src='../images/arxiv-logo-16px-high.png'/>";
$BibtexBibtexButton = "class='btn btn--mcwbibtex'><img src='../images/BibTeX_logo-16px-high.png'/>";

$BibtexGenerateDefaultUrlField = false;

//$TitleLinkDOIURL = true;   // added -MCW 04/21/08
$TitleLinkDOIURL = false;

$BibtexLang = array();

/* PmWiki calls
Markup("bibtexcite","inline","/\\{\\[(.*?),(.*?)\\]\\}/e","BibCite('$1', '$2')");
Markup("bibtexquery","fulltext","/\\bbibtexquery:\\[(.*?)\\]\\[(.*?)\\]\\[(.*?)\\]\\[(.*?)\\]/e","BibQuery('$1', '$2', '$3', '$4')");
Markup("bibtexsummary","fulltext","/\\bbibtexsummary:\\[(.*?),(.*?)\\]/e","BibSummary('$1', '$2')");
Markup("bibtexcomplete","fulltext","/\\bbibtexcomplete:\\[(.*?),(.*?)\\]/e","CompleteBibEntry('$1', '$2')");

SDV($HandleActions['bibentry'],'HandleBibEntry');
*/

$BibEntries = array();

class BibtexEntry {
    var $values = array();
    var $bibfile;
    var $entryname;
    var $entrytype;

    //    function BibtexEntry($bibfile, $entryname)
    function __construct($bibfile, $entryname)
    {
      $this->bibfile = $bibfile;
      $this->entryname = $entryname;
    }

    function evalCond($cond)
    {
      $toeval = "return (" . $cond . ");";
      $toeval = str_replace("&gt;", ">", $toeval);
      return eval($toeval);
    }

    function evalGet($get)
    {
       $get = str_replace("\\\"", "\"", $get);
       $get = str_replace("&gt;", ">", $get);
       eval('$res = ' . $get . ';');
      return $res;
    }

    // MCW - July 3, 2021 - month function
    function shortMonth($month)
    {
        if (strlen($month) == 3) {
          // this is an auto month - added MCW 03/22/08
          if ($month == "jan")
            $month = "January";
          else if ($month == "feb") {
            $month = "February";
          }
          else if ($month == "mar") {
            $month = "March";
          } 
          else if ($month == "apr") {
            $month = "April";
          } 
          else if ($month == "may") {
            $month = "May";
          }
          else if ($month == "jun") {
            $month = "June";
          }
          else if ($month == "jul") {
            $month = "July";
          }
          else if ($month == "aug") {
            $month = "August";
          }
          else if ($month == "sep") {
            $month = "September";
          }
          else if ($month == "oct") {
            $month = "October";
          }
          else if ($month == "nov") {
            $month = "November";
          }
          else if ($month == "dec") {
            $month = "December";
          }
        }
        return $month;
    }

    function getAuthors()
    {
      $aut = $this->get('AUTHOR');
      if ($aut == FALSE) return FALSE;
      $aut = explode(" and ", $aut);

      $ret = "";

      for ($i = 0; $i < count($aut); $i++)
        {
	   $ret = $ret . $aut[$i];
          if ($i == count($aut) - 2) 
            $ret = $ret . ", and ";  // added Oxford comma -MCW 2/19/2020
          else if ($i < count($aut) - 2) 
            $ret = $ret . ", ";
        }
      return $ret;
    }

    function getEditors()
    {
      $edi = $this->get('EDITOR');
      if ($edi == FALSE) return FALSE;
      $edi = explode(" and ", $edi);

      $ret = "";

      for ($i = 0; $i < count($edi); $i++)
        {
      $ret = $ret . $edi[$i];
      if ($i == count($edi) - 2) $ret = $ret . " and ";
      else if ($i < count($edi) - 2) $ret = $ret . ", ";
        }
      return $ret;
    }
    
    function getName()
    {
      return $this->entryname;
    }

    function getTitle()
    {
      return $this->getFormat('TITLE');
    }

    function getAbstract()
    {
      return $this->get('ABSTRACT');
    }

    function getComment()
    {
      return $this->get('COMMENT');
    }

    function getPages()
    {
      $pages = $this->get('PAGES');
      if ($pages)
      {
          $found = strpos($pages, "--");
          if ($found)
                return str_replace("--", "-", $pages);
          else
                return $pages;
      }
      return "";
    }

    function getPagesWithLabel()
    {
        $pages = $this->getPages();
        if ($pages)
        {
            if (is_numeric($pages[0]) && strpos($pages, "-")) 
	      //                return "pages " . $pages;
	      return "pp. " . $pages;
            elseif (is_numeric($pages))
	      //                return "page " . $pages;
	      return "p. " . $pages;
        }
        return $pages;
    }
    
    function get($field)
    {
      // MCW: Jul 3, 2021 -- fix logic here so don't access non-existent array key
      if (!array_key_exists($field, $this->values)) {
          if (!array_key_exists(strtolower($field), $this->values)) {
            return FALSE;
          } else {
            $val = $this->values[strtolower($field)];
          }
      } else {
          $val = $this->values[$field];
      }
      return trim($val);
    }


    function getFormat($field)
    {
      $ret = $this->get($field);
      if ($ret)
      {
        $ret = str_replace("{", "", $ret);
        $ret = str_replace("}", "", $ret);
      }
      return $ret;
    }

    function getCompleteEntryUrl()
    {
      global $DefaultTitle, $FarmD, $BibtexCompleteEntriesUrl;
      global $pagename;

      $Bibfile = $this->bibfile;
      $Entryname = $this->entryname;

      if ($Entryname != " ")
      {
        if (!$BibtexCompleteEntriesUrl) 
            $BibtexCompleteEntriesUrl = "/publications/bibtex#\$Entryname";

        $RetUrl = preg_replace('/\$Bibfile/', "$Bibfile", $BibtexCompleteEntriesUrl);
        $RetUrl = preg_replace('/\$Entryname/', "$Entryname", $RetUrl);
      }
      return $RetUrl;
    }

    function getPreString()
    {
      // *****************************
      // Add LANG, AUTHOR, YEAR, TITLE
      // The golden rule is to always insert a punctuation BEFORE a field not AFTER
      // because you're never sure there is going to be something after the field inserted.
      // *****************************
      global $pagename, $BibtexLang, $TitleLinkDOIURL;
      $ret = "";

      $lang = $this->get("LANG");
      if ($lang && $BibtexLang[$lang])
      {
          $ret = $ret . $BibtexLang[$lang];
      }

      $author = $this->getAuthors(); 
      $ret = $ret . $author;

      /*  // commented -MCW 09/15/08
      $year = $this->get("YEAR");
      if ($year)
	{	    
	  $ret = $ret . " (";
	  $ret = $ret . $year . ") ";
	}
      */

      if ($this->getTitle() != "")
      {
          // bold title
          //$ret = $ret . ", \"" . $this->getTitle();
          $ret = $ret . ", \"**" . $this->getTitle() . "**";
        
     //   if (strlen($ret) > 2 && $ret[strlen($ret) - 1] != '?')
          if (strlen($ret) > 2)  // MCW: put comma after titles ending w/? too
            $ret = $ret . ",";

        $ret = $ret . "\"";
      }

      return $ret;
  }

  function getPostString($dobibtex = true)
  {
      // *****************************************
      // Add a point, NOTE, URL, PDF and BibTeX
      // The golden rule is to always insert a punctuation BEFORE a field not AFTER
      // because you're never sure there is going to be something after the field inserted.
      // *****************************************

      global $ScriptUrl, $BibtexUrlLink, $BibtexBibLink, $BibtexBibtexButton,  
          $BibtexPreprintLink, $BibtexLinkIcon, $BibtexSlideshare, $BibtexPdfIcon, $pagename, $TitleLinkDOIURL;

      $ret = ".";

      $award = $this->get("AWARD");
      if ($award) 
      {
          $ret = $ret . " ***" . $award . " Award***. ";
      }

      if (!$TitleLinkDOIURL) {
	    // Don't add (URL) (DOI) to end if given in title link  -MCW 04/21/08
           $url = $this->get("URL");

          if ($url) 
          {
 //            $ret = $ret . " ([$BibtexUrlLink](" . $url . "))";
             $ret = $ret . " <a href='" . $url . "' target='_blank'>" . $BibtexLinkIcon . "</a>";
          }

          $doi = $this->get("DOI");
          if ($doi)
          {
             global $BibtexDoiUrl, $BibtexDoiLink, $BibtexDoiIcon, $UploadUrlFmt;
	     //             if (!$BibtexDoiUrl) $BibtexDoiUrl = FmtPageName('$UploadUrlFmt$UploadPrefixFmt', $pagename);
	     //            $ret = $ret . " [[$BibtexDoiUrl" . $doi . " | $BibtexDoiLink]][==]";

		        # if DOI is just the number, append http://dx.doi.org/
		        $httppos = strpos ($doi, "http://");
		        if ($httppos === false) {
		          $doi = "http://dx.doi.org/" . $doi;
		        }
            $ret = $ret . " <a href='" . $doi . "' target='_blank'>" . $BibtexDoiIcon . "</a>";
//           $ret = $ret . " [" . $BibtexDoiLink . "[(". $doi . ")";
          }
      }

      if ($this->entryname != " ") {
        $openparen = false;
	      # add PDF, slides, preprint, poster, trip report before BibTeX
	      $poster = $this->get("POSTER");                                               
	      if ($poster) {                                                                
	        global $BibtexPosterUrl, $BibtexPosterLink, $UploadUrlFmt;   
          if (! $openparen) {
            $ret = $ret . " (";
            $openparen = true;
          } else {
            $ret = $ret . ", ";
          }  
          $ret = $ret . "[" . $BibtexPosterLink . "](" . $poster . ")";                                                                                	
        }             
        if ($openparen) {
          $ret = $ret . ")";
        }

        $preprint = $this->get("PREPRINT");
	      if ($preprint) {
	        global $BibtexPreprintUrl, $BibtexPdfIcon, $BibtexPreprintLink, $UploadUrlFmt;
          $ret = $ret . " <a href='" . $preprint . "' target='_blank'>" . $BibtexPdfIcon . "</a>";
//          $ret = $ret . "[" . $BibtexPreprintLink . "](" . $preprint . ")";
        }

        $pdf = $this->get("PDF");
	      if ($pdf) {
	        global $BibtexPdfUrl, $BibtexPdfLink, $BibtexPdfIcon, $UploadUrlFmt;
//	        $ret = $ret . "[" . $BibtexPdfLink . "](". $pdf . ")";
          $ret = $ret . " <a href='" . $pdf . "' target='_blank'>" . $BibtexPdfIcon . "</a>";
	      }

        $arxiv = $this->get("ARXIV");
	      if ($arxiv) {
          global $BibtexArxivUrl, $BibtexArxivLink, $BibtexArxivButton, $UploadUrlFmt;
          $ret = $ret . " &nbsp;<a href='" . $arxiv . "' target='_blank' " . $BibtexArxivButton . "</a>";
//	        $ret = $ret . "[" . $BibtexArxivLink . "](" . $arxiv . ")" . ", ";
        }

        $slides = $this->get("SLIDES");
	      if ($slides) {
	        global $BibtexSlidesUrl, $BibtexSlidesLink, $BibtexSlideshareButton, $UploadUrlFmt;
          $ret = $ret . " <a href='" . $slides . "' target='_blank' " . $BibtexSlideshareButton . "</a>";
//	        $ret = $ret . "[" . $BibtexSlidesLink . "](" . $slides . ")";
	      }

        $tripreport = $this->get("TRIPREPORT");
	      if ($tripreport) {
	        global $BibtexTripReportUrl, $BibtexTripReportLink, $BibtexTripIcon, $UploadUrlFmt;
          $ret = $ret . " <a href='" . $tripreport . "' target='_blank'>" . $BibtexTripIcon . "</a>";
//	        $ret = $ret . "[" . $BibtexTripReportLink . "](" . $tripreport . ")";
	      }

        if ($dobibtex) {
          // BibTeX button
          $ret = $ret . " &nbsp;<a href='" . $this->getCompleteEntryUrl() . "' target='_blank' " . $BibtexBibtexButton . "</a>";
//	      $ret = $ret . "[" . $BibtexBibLink . "](" . $this->getCompleteEntryUrl() . "))";
        }
      }
      return $ret;
    }

    function cite()
    {
      $ret = "([" . $this->entryname . "](" . $this->getCompleteEntryUrl() . "))";
      return $ret;
    }

    function getBibEntry()
    {
      global $OrigBibEntries;
      global $BibtexSilentFields, $BibtexGenerateDefaultUrlField;
      $INDENT = "    ";

      // MCW 2022-02-07 - adding {% raw %} to escape double-braces for Jekyll
      $ret = "{% raw %}\n\n```bibtex\n@" . $this->entrytype . " {" . $this->entryname . ",\n$INDENT";

      // MCW 2022-02-06 - for every *, replace with , then newline and indent
      $one_bib_entry = $OrigBibEntries[$this->entryname];
      $commas_bib_entry = preg_replace("/\*/", ",\n$INDENT", $one_bib_entry);
      $ret = $ret . $commas_bib_entry;
      $ret = $ret . "\n}\n```\n\n{% endraw %}\n\n"; // MCW 2022-02-07 - adding {% endraw %} escape

      /*
      //while (list($key, $value)=each($this->values))  // each deprecated  MCW, Jul 4, 2021
      foreach ($this->values as $key => $value)
      {
       if ($BibtexSilentFields && in_array($key, $BibtexSilentFields)) continue;
       $ret = $ret . $INDENT . $key . " = {" . $value . "},\n";
      }

      if ($BibtexGenerateDefaultUrlField && ($this->get("URL") == false)) 
        $ret = $ret . $INDENT . "URL = {" . $this->getCompleteEntryUrl() . "},\n";
      $ret = $ret . "}\n```\n";
    */

      return $ret;
    }

    function getCompleteEntry()
    {
      $ret = "## [" .  $this->entryname . "](#" . $this->entryname . ")\n\n";
      $ret = $ret . $this->getSummary(false);

      $abstract = $this->getAbstract();
      if ($abstract)
      {
        $ret = $ret . "\n\n**Abstract:**\n\n   " . $abstract;
      }
      $comment = $this->getComment();
      if ($comment)
      {
        $ret = $ret . "\n\n**Comment:**\n\n   " . $comment;
      }

      $ret = $ret . "\n\n[](#" . $this->entryname . "Bib)\n";
      $ret = $ret . "**BibTeX entry:**\n\n" . $this->getBibEntry();
      return $ret;
    }

    function getSolePageEntry()
    {
      $ret = "!" . $this->entryname . "\n";
      $ret = $ret . "\n!!!Summary\n";
      $ret = $ret . $this->getSummary(false) . "\n";
      $abstract = $this->getAbstract();
      if ($abstract)
      {
        $ret = $ret . "\n!!!Abstract\n" . $abstract . "\n";
      }
      $comment = $this->getComment();
      if ($comment)
      {
        $ret = $ret . "\n!!!Comment\n" . $comment . "\n";
      }
      $ret = $ret . "[[#" . $this->entryname . "Bib]]\n";
      $ret = $ret . "\n!!!Bibtex entry\n" . $this->getBibEntry() . "\n";
      return $ret;
    }
  }

  class PhdThesis extends BibtexEntry {
    function __construct($bibfile, $entryname)
    //  function PhdThesis($bibfile, $entryname)
    {
      parent::__construct($bibfile, $entryname);
      $this->entrytype = "PHDTHESIS";
    }

    function getSummary($dobibtex = true)
    {
      $ret = parent::getPreString();
      $ret = $ret . " PhD thesis";
      $school = parent::get("SCHOOL");
      if ($school)
      {
        $ret = $ret . ", *" . $school . "*";
      }

	$month = parent::get("MONTH");

  if ($month) {
    $month = parent::shortMonth($month);    // this is an auto month - added MCW 03/22/08
    if ($ret[strlen($ret) - 1] != '.')
	    $ret = $ret . ",";
	  
	  $ret = $ret . " " . $month;
	}

	$year = parent::get("YEAR");
	if ($year) {
	  $ret = $ret . " " . $year;
	}


      return $ret . parent::getPostString($dobibtex);
    }
  }

// *****************************
// MasterThesis
// *****************************
class MasterThesis extends BibtexEntry {

  //  function MasterThesis($bibfile, $entryname)
      function __construct($bibfile, $entryname)
  {
    parent::__construct($bibfile, $entryname);
    $this->entrytype = "MASTERSTHESIS";
  }

  function getSummary($dobibtex = true)
  {
    $ret = parent::getPreString();

    $ret = $ret . " Master's thesis";
    $school = parent::get("SCHOOL");
    if ($school)
    {
      $ret = $ret . ", *" . $school . "*";
    }

	// added month and year -MCW 07/21/09
	$month = parent::get("MONTH");
	if ($month) {
    $month = parent::shortMonth($month);    // this is an auto month - added MCW 03/22/08
	  
	  if ($ret[strlen($ret) - 1] != '.')
	    $ret = $ret . ",";
	  
	  $ret = $ret . " " . $month;
	}

	$year = parent::get("YEAR");
	if ($year) {
	  $ret = $ret . " " . $year;
	}

    return $ret . parent::getPostString($dobibtex);
  }
}

// *****************************
// TechReport
// *****************************
class TechReport extends BibtexEntry {
  //  function TechReport($bibfile, $entryname)
      function __construct($bibfile, $entryname)
  {
    parent::__construct($bibfile, $entryname);
    $this->entrytype = "TECHREPORT";
  }

  function getSummary($dobibtex = true)
  {
    $ret = parent::getPreString();
    $type = parent::get("TYPE");
    if ( $type )
       $ret = $ret . " $type";
    else
       $ret = $ret . " Technical report";
    
    $number = parent::get("NUMBER");
    if ($number)
       $ret = $ret . " $number";
    $institution = parent::get("INSTITUTION");
    if ($institution)
    {
      $ret = $ret . ", " . $institution;
    }

// MCW (6/22/17) - adding month/year to output
    $month = parent::get("MONTH");
    if ($month) {
      $month = parent::shortMonth($month);    // this is an auto month - added MCW 03/22/08

      if ($ret[strlen($ret) - 1] != '.')
	$ret = $ret . ",";
      
      $ret = $ret . " " . $month;
    }
    
    $year = parent::get("YEAR");
    if ($year) {
      $ret = $ret . " " . $year;
    }

    return $ret . parent::getPostString($dobibtex);
  }
}

// *****************************
// Article
// *****************************
class Article extends BibtexEntry {
  //  function Article($bibfile, $entryname)
      function __construct($bibfile, $entryname)
  {
    parent::__construct($bibfile, $entryname);
    $this->entrytype = "ARTICLE";
  }

  function getSummary($dobibtex = true)
  {
    $ret = parent::getPreString();
    $journal = parent::get("JOURNAL");
    if ($journal)
    {
      // make journal italics -MCW 04/25/08
      $ret = $ret . " *" . $journal . "*";  
      // change volume and number format from vol(num) to Vol. vol, No. num,
      // MCW 09/15/08
      $volume = parent::get("VOLUME");
      if ($volume)
      {
	//        $ret = $ret . ", " . $volume;
	$ret = $ret . ", Vol. " . $volume;
      }
        $number = parent::get("NUMBER");
        if ($number)
        {
	  //          $ret = $ret . "(" . $number . ")";
	  $ret = $ret . ", No. " . $number;
        }
	// added month and year -MCW 09/15/08
	$month = parent::get("MONTH");
	if ($month) {
    $month = parent::shortMonth($month);    // this is an auto month - added MCW 03/22/08
	  
	  if ($ret[strlen($ret) - 1] != '.')
	    $ret = $ret . ",";
	  
	  $ret = $ret . " " . $month;
	}

	$year = parent::get("YEAR");
	// modified 1/6/2010 -MCW
	if ($year) {
          if ($month) {
              $ret = $ret . " " . $year;
          } else {
             if ($ret[strlen($ret) - 1] != '.')
               $ret = $ret . ",";
  	     $ret = $ret . " " . $year;
          }
	}

        $pages = parent::getPagesWithLabel();
        if ($pages)
        {
	  // change page format -MCW 09/15/08
          $ret = $ret . ", " . $pages;
        }
    }
    return $ret . parent::getPostString($dobibtex);
  }
}

// *****************************
// InProceedings
// *****************************
class InProceedings extends BibtexEntry
{
  //    function InProceedings($bibfile, $entryname)
      function __construct($bibfile, $entryname)
    {
      parent::__construct($bibfile, $entryname);
      $this->entrytype = "INPROCEEDINGS";
    }

    function getSummary($dobibtex = true)
    {
        $ret = parent::getPreString();
        $booktitle = parent::get("BOOKTITLE");

        if ($booktitle)
        {
      	  // make proceedings italics -MCW 4/25/08
	          $ret = $ret . " In *" . $booktitle . "*.";

            $address = parent::get("ADDRESS");
            if ($address)
            {
                if ($ret[strlen($ret) - 1] != '.')
                    $ret = $ret . ".";

                $ret = $ret . " " . $address;
            }
            
            $month = parent::get("MONTH");
            if ($month)
            {
              $month = parent::shortMonth($month);    // this is an auto month - added MCW 03/22/08

	            if ($ret[strlen($ret) - 1] != '.')
		            $ret = $ret . ",";

	            $ret = $ret . " " . $month;
            }
            
	          // added -MCW 09/15/08
            $year = parent::get("YEAR");
	          if ($year) {
	            $ret = $ret . " " . $year;
	          }

            $editor = parent::get("EDITOR");
            if ($editor)
            {
                if ($ret[strlen($ret)-1] != '.')
                    $ret = $ret . ".";

                $ret = $ret . " (" . $editor .", Eds.)";
            }

            $publisher = parent::get("PUBLISHER");
            if ($publisher)
            {
                if ($ret[strlen($ret)-1] != ')')
                    $ret = $ret . ".";
                $ret = $ret . " " . $publisher;
            }

            $pages = $this->getPagesWithLabel();
            if ($pages)
            {
                if ($ret[strlen($ret) - 1] != ')')
                    $ret = $ret . ",";
                elseif ($pages[0] == 'p')
                    $pages[0] = 'P';

                $ret = $ret . " " . $pages;
            }

	          // replace ORGANIZATION with NOTE  MCW 7/30/09   
            $note = parent::get("NOTE");
            if ($note)
            {
                if ($ret[strlen($ret) - 1] != ')')
                    $ret = $ret . ", ";
                $ret = $ret . $note;
            }
        }

        return $ret . parent::getPostString($dobibtex);
    }

}

// *****************************
// InCollection
// *****************************
class InCollection extends BibtexEntry {
  //  function InCollection($bibfile, $entryname)
      function __construct($bibfile, $entryname)
  {
    parent::__construct($bibfile, $entryname);
    $this->entrytype = "INCOLLECTION";
  }

  function getSummary($dobibtex = true)
  {
    $ret = parent::getPreString();
    $booktitle = parent::get("BOOKTITLE");
    if ($booktitle)
    {
//        MCW 4/23/09 - make booktitle italicized
      $ret = $ret . " In *" . $booktitle . "*";

      $editor = $this->getEditors();
      if ($editor) {
         $ret = $ret . ", " . $editor . ", Eds. ";
      }

      $pages = $this->getPagesWithLabel();
      if ($pages)
        $ret = $ret . ", " . $pages . ". ";

      $publisher = parent::get("PUBLISHER");
      if ($publisher)
      {
// MCW 4/23/09 -- don't know what this does
//            if ($ret[strlen($ret)-1] != '.')
//                $ret = $ret . ". ";
            $ret = $ret . $publisher;
      }

// MCW 8/4/09 -- add date
      $year = $this->get("YEAR");
      if ($year)
      {           
        $ret = $ret . ", " . $year;
      }

    }
    return $ret . parent::getPostString($dobibtex);

  }

}

// *****************************
// Book
// *****************************
class Book extends BibtexEntry {
  //    function Book($bibfile, $entryname)
      function __construct($bibfile, $entryname)
    {
      parent::__construct($bibfile, $entryname);
      $this->entrytype = "BOOK";
    }

    function getSummary($dobibtex = true)
    {

      global $TitleLinkDOIURL;

// MCW 2/27/09
// Book should be either
// auth, italicized title, publisher, date
// OR
// eds, italicized title, publisher, date

//        $ret = $ret . parent::getPreString($dourl);

      $author = $this->getAuthors(); 
      $editor = $this->getEditors();

      if ($author) {
         $ret = $author;
      } else if ($editor) {
         $ret = $editor . ", Eds.";
      }
        
      if ($this->getTitle() != "")
      {
         if ($TitleLinkDOIURL) {
              // if DOI or URL given, make link on title -MCW 04/21/08
              $doi = $this->get("DOI");
              $url = $this->get("URL");
              if ($doi) {
		            # if DOI is just the number, append http://dx.doi.org/
		            $httppos = strpos ($doi, "http://");
		            if ($httppos === false) {
		              $doi = "http://dx.doi.org/" . $doi;
		            }

                $ret = $ret . ", [*" . $this->getTitle() . "*](" . $doi . ")";
              } else if ($url) {
                $ret = $ret . ", [*" . $this->getTitle() . "*](" . $url . ")";
              } 
              else
                  $ret = $ret . ", *" . $this->getTitle() . "*";
          }
          else
              $ret = $ret . ", *" . $this->getTitle() . "*";

          if (strlen($ret) > 2 && $ret[strlen($ret) - 1] != '?')
              $ret = $ret . ",";
      }

     if ($editor && $author)
           $ret = $ret . " (" . $editor .", Eds.)";
        
        $publisher = parent::get("PUBLISHER");
        if ($publisher)
            $ret = $ret . " " . $publisher;

         $address = parent::get("ADDRESS");
         if ($address)
         {
             if ($ret && $ret[strlen($ret) - 1] != "." && $ret[strlen($ret) - 1] != "'")
                $ret = $ret . ",";
             $ret = $ret . " $address";
         }

        $year = $this->get("YEAR");
        if ($year)
        {           
          $ret = $ret . ", " . $year;
        }

        // Remove the point at the end of the string if only the title was provided
        if ($ret && $ret[strlen($ret) - 3] == '.')
            $ret = substr_replace($ret, "", strlen($ret) - 3, 1);
        
        $post = parent::getPostString($dobibtex);
        $ret = $ret . $post;
        
        return $ret;
    }
}

// *****************************
// InBook
// *****************************
class InBook extends BibtexEntry {
  //  function InBook($bibfile, $entryname)
      function __construct($bibfile, $entryname)
  {
    parent::__construct($bibfile, $entryname);
    $this->entrytype = "INBOOK";
  }

 
    function getSummary($dobibtex = true)
    {
        $ret = parent::getPreString();
        $booktitle = parent::get("BOOKTITLE");
        if ($booktitle)
        {
            $ret = $ret . " In *" . $booktitle . "*.";
           
            $editor = parent::get("EDITOR");
            if ($editor)
            {
                if ($ret[strlen($ret)-1] != '.')
                    $ret = $ret . ".";

                $ret = $ret . " (" . $editor .", Eds.)";
            }

            $address = parent::get("ADDRESS");
            if ($address)
            {
                if ($ret[strlen($ret) - 1] != '.')
                    $ret = $ret . ".";

                $ret = $ret . " " . $address;
            }
            
            $publisher = parent::get("PUBLISHER");
            if ($publisher)
            {
                if ($ret[strlen($ret)-1] != ',')
                    $ret = $ret . ",";
                $ret = $ret . " " . $publisher;
            }

            $year = $this->get("YEAR");
            if ($year)
            {           
              $ret = $ret . ", " . $year;
            }

            $pages = $this->getPagesWithLabel();
            if ($pages)
            {
                if ($ret[strlen($ret) - 1] != ')')
                    $ret = $ret . ",";
                elseif ($pages[0] == 'p')
                    $pages[0] = 'P';

                $ret = $ret . " " . $pages;
            }

            $organization = parent::get("ORGANIZATION");
            if ($organization)
            {
                if ($ret[strlen($ret) - 1] != ')')
                    $ret = $ret . ", ";
                $ret = $ret . ". " . $organization;
            }
        }

        return $ret . parent::getPostString($dobibtex);
    }

}

// *****************************
// Proceedings
// *****************************
class Proceedings extends BibtexEntry {
  //      function Proceedings($bibfile, $entryname)
      function __construct($bibfile, $entryname)
      {
         parent::__construct($bibfile, $entryname);
         $this->entrytype = "PROCEEDINGS";
      }

      function getSummary($dobibtex = true)
      {
         $ret = parent::getPreString();
         $editor = parent::get("EDITOR");
         if ($editor)
             $ret = $ret . " (" . $editor .", Eds.)";

         $volume = parent::get("VOLUME");
         if ($volume)
         {
            $ret = $ret . "volume " . $volume;
            $series = parent::get("SERIES");
            if ( $series != "" )
               $ret = $ret . " of *$series*";
         }
         $address = parent::get("ADDRESS");
         if ($address)
            $ret = $ret . ", $address";
         $orga = parent::get("ORGANIZATION");
         if ($orga)
            $ret = $ret . ", $orga";
         $publisher = parent::get("PUBLISHER");
         if ($publisher)
            $ret = $ret . ", $publisher";
         $ret = $ret . parent::getPostString($dobibtex);
         return $ret;
      }
}

// *****************************
// Misc
// *****************************
class Misc extends BibtexEntry {
  //  function Misc($bibfile, $entryname)
      function __construct($bibfile, $entryname)
  {
    parent::__construct($bibfile, $entryname);
    $this->entrytype = "MISC";
  }

  function getSummary($dobibtex = true)
  {
    $ret = parent::getPreString();
    
    $howpublished = parent::get("HOWPUBLISHED");
    if ($howpublished)
        $ret = $ret . " " . $howpublished;

    $month = parent::get("MONTH");
    if ($month) {
      $month = parent::shortMonth($month);    // this is an auto month - added MCW 03/22/08
      
      if ($ret[strlen($ret) - 1] != '.')
	$ret = $ret . ",";
      
      $ret = $ret . " " . $month;
    }
    
    $year = parent::get("YEAR");
    if ($year) {
      $ret = $ret . " " . $year;
    }

    $ret = $ret . parent::getPostString($dobibtex);
    return $ret;
  }
}

function sortByField($a, $b)
{
  global $SortField;
  $f1 = $a->evalGet($SortField);
  $f2 = $b->evalGet($SortField);

  if ($f1 == $f2) return 0;

  return ($f1 < $f2) ? -1 : 1;
}

function BibQuery($files, $cond, $sort, $max)
{
    global $BibEntries, $SortField;

    $ret = '';

    $files = trim($files);
    $cond = trim($cond);
    $sort = trim($sort);

    if ($sort[0] == '!')
    {
        $reverse = true; $sort = substr($sort, 1);
    }
    else $reverse = false;

    if ($cond == '') $cond = 'true';

    if (!array_key_exists($files, $BibEntries)) {
        if (!ParseBibFile($files))
            return "%red%Invalid BibTex File!";
    }

    $res = array();
    $bibselectedentries = $BibEntries[$files];
    // while (list($key, $value)=each($bibselectedentries)) // each deprecated  MCW, Jul 4, 2021
    foreach ($bibselectedentries as $key => $value)
    {

        if ($value->evalCond($cond))
            $res[] = $value;
    }

    if ($sort != '')
    {
        $SortField = $sort;
        usort($res, "sortByField");
    }

    if ($reverse)
        $res = array_reverse($res);

    if ($max != '')
        $res = array_slice($res, 0, (int) $max);

    //while (list($key, $value)=each($res)) // each deprecated  MCW, Jul 4, 2021
    foreach ($res as $key => $value)
    {
        // Add class to allow CSS styling -- SGA 5/4/11
//        $ret .= '#%list class=bibquery%' . $value->getSummary() . "\n";
        $ret .= "1. " . $value->getSummary() . "\n";
    }

    return $ret;
}

// Below not used without PmWiki.  MCW Jul 4, 2021 
/* 
function HandleBibEntry($pagename)
{
  global $HTTP_GET_VARS, $PageStartFmt, $PageEndFmt, $PageHeaderFmt, $ScriptUrl, $bibentry, $bibfile, $bibref;
  //$bibfile = $HTTP_GET_VARS['bibfile'];
  //  $bibref = $HTTP_GET_VARS['bibref'];
  $bibfile = $_GET["bibfile"];
  $bibref = $_GET["bibref"];
  SDV($ScriptUrl, FmtPageName('$PageUrl', $pagename));

  $bibentry = GetEntry($bibfile, $bibref);

  $page = array('timefmt'=>@$GLOBALS['CurrentTime'],
		'author'=>@$GLOBALS['Author']);

  $PageHeaderFmt = "";
  SDV($HandleBibtexFmt,array(&$PageStartFmt,
    'function:PrintCompleteEntry',&$PageEndFmt));
  PrintFmt($pagename,$HandleBibtexFmt);
}

function PrintCompleteEntry()
{
  global $bibentry, $bibfile, $bibref, $pagename;
  if ($bibentry == false) echo MarkupToHTML($pagename, "%red%Invalid BibTex Entry: [" . $bibfile . ", " . $bibref . "]!");
  else
    {
      echo MarkupToHTML($pagename, $bibentry->getSolePageEntry());
    }
}
*/

function GetEntry($bib, $ref)
{
    global $BibEntries;

    $ref = trim($ref);
    $bib = trim($bib);
    // MCW: Jul 4, 2021 - fix logic so don't access non-existent key
    if (!array_key_exists($bib, $BibEntries)) {
      ParseBibFile($bib);
    }
    $bibtable = $BibEntries[$bib];
 
    reset($bibtable);

//    while (list($key, $value)=each($bibtable))  // each deprecated  MCW, Jul 4, 2021
    foreach ($bibtable as $key => $value)
    {
        if ($value->getName() == $ref)
        {
            $bibref = $value;
            break;
        }
    }

    if ($bibref == false)
      return false;
    return $bibref;
}

function BibCite($bib, $ref)
{
  $entry = GetEntry($bib, $ref);
  if ($entry == false) return "%red%Invalid BibTex Entry!";
  return $entry->cite();
}

function CompleteBibEntry($bib, $ref)
{
  $entry = GetEntry($bib, $ref);
  if ($entry == false) return "%red%Invalid BibTex Entry!";
  return $entry->getCompleteEntry();
}

function BibSummary($bib, $ref, $dobibtex='true')
{
  $entry = GetEntry($bib, $ref);
  if ($entry == false) return "%red%Invalid BibTex Entry!";
  return $entry->getSummary($dobibtex);
}

function ParseEntries($fname, $entries)
{
   global $BibEntries;

     // MCW 2022-02-06
  global $OrigBibEntries;
  $OrigBibEntries = [];

   $nb_entries = count($entries[0]);

   $bibfileentry = array();
   for ($i = 0 ; $i < $nb_entries ; ++$i)
   {
      $entrytype = strtoupper($entries[1][$i]); 

      $entryname = $entries[2][$i];

      //if ($i < 5)
      //  print "<font color=#FF0000>Allo nb_entries=$nb_entries entryname=$entryname</font><br>\n";
        
      if ($entrytype == "ARTICLE") $entry = new Article($fname, $entryname);
      else if ($entrytype == "INPROCEEDINGS") $entry = new InProceedings($fname, $entryname);
      else if ($entrytype == "PHDTHESIS") $entry = new PhdThesis($fname, $entryname);
      else if ($entrytype == "MASTERSTHESIS") $entry = new MasterThesis($fname, $entryname);
      else if ($entrytype == "INCOLLECTION") $entry = new InCollection($fname, $entryname);
      else if ($entrytype == "BOOK") $entry = new Book($fname, $entryname);
      else if ($entrytype == "INBOOK") $entry = new InBook($fname, $entryname);
      else if ($entrytype == "TECHREPORT") $entry = new TechReport($fname, $entryname);
      else if ($entrytype == "PROCEEDINGS") $entry = new Proceedings($fname, $entryname);
      else if ($entrytype == "MISC") $entry = new Misc($fname, $entryname);
      else $entry = new Misc($fname, $entryname);

      // MCW 2022-02-06 - save original BibTeX text
     $OrigBibEntries[$entryname] = $entries[3][$i];

      // match all keys
//      preg_match_all("/(\w+)\s*=\s*([^�]+)�?/", $entries[3][$i], $all_keys);
      preg_match_all("/(\w+)\s*=\s*([^\*]+)\*?/", $entries[3][$i], $all_keys);
      // MCW: change char to \*
      
      for ($j = 0 ; $j < count($all_keys[0]) ; $j++)
      {
        $key = strtoupper($all_keys[1][$j]);
        $value = $all_keys[2][$j];
        // Remove the leading and ending braces or quotes if they exist.
        $value = preg_replace('/^\s*{(.*)}\s*$/', '\1', $value);
        // TODO: only run this regexp if the former didn't match
        $value = preg_replace('/^\s*"(.*)"\s*$/', '\1', $value);
        // Remove embedded braces unless escaped -SGA 5/4/11
        $value = preg_replace('/(?<!\\\\)[{}]/', '', $value);
        // Remove escapes -SGA 5/4/11
        // TODO: handle specific escapes
        $value = preg_replace('/\\\\/', '', $value);

        $entry->values[$key] = $value;
      }
      
//$val = "<font color=#FF0000>char = " . $entry->values["AUTHOR"][2]. $entry->values["AUTHOR"][3]. $entry->values["AUTHOR"][4] . "</font><br><br>\n";
//print $val;
      $bibfileentry[] = $entry;
    }

   $BibEntries[$fname] = $bibfileentry;
}


function ParseBib($bib_file, $bib_file_string)
{
// first split the bib file into several part
// first let's do an ugly trick to replace the first { and the last } of each bib entry by another special char (to help with regexp)

  /* MCW - Jul 3, 2021 - replace '�' with '*' */
  //$DELIM = '�';
  $DELIM = '*';

   $count=0;
   for ($i = 0 ; $i < strlen($bib_file_string) ; $i++)
   {
      if ($bib_file_string[$i] == '{')
      {
         if ($count==0)
            $bib_file_string[$i] = $DELIM;
         $count++;
      }
      else if ($bib_file_string[$i] == '}')
      {
         $count--;
         if ($count==0)
              $bib_file_string[$i] = $DELIM;
      }
      else if ($bib_file_string[$i] == ',' && $count == 1)
      {
          $bib_file_string[$i] = $DELIM;
      }
      else if ($bib_file_string[$i] == "\r" && $count == 1)
              $bib_file_string[$i] = $DELIM;
   }

   //   $bib_file_string = preg_replace("/��/", "�", $bib_file_string);
   $bib_file_string = preg_replace("/\*\*/", $DELIM, $bib_file_string);

//    $nb_bibentry = preg_match_all("/@(\w+)\s*�\s*([^�]*)�([^�]*)�/", $bib_file_string, $matches);
   $nb_bibentry = preg_match_all("/@(\w+)\s*\*\s*([^\*]*)\*([^@]+)\*/", $bib_file_string, $matches);

   ParseEntries($bib_file, $matches);
}

function ParseBibFile($bib_file)
{
  global $BibEntries;
  
  // Below not used without PmWiki.  MCW Jul 4, 2021 
/*
    global $BibtexBibDir, $pagename;

    $wikibib_file = MakePageName($pagename, $bib_file);

    if (PageExists($wikibib_file))
    {
        $bib_file_string = ReadPage($wikibib_file, READPAGE_CURRENT);
        $bib_file_string = $bib_file_string['text'];

        $bib_file_string = preg_replace("/\n/", "\r", $bib_file_string); // %0a

        ParseBib($bib_file, $bib_file_string);
        return true;
    }
    else
    {
        if (!$BibtexBibDir)
            $BibtexBibDir = FmtPageName('$UploadDir$UploadPrefixFmt', $pagename);
    
*/

//        if (file_exists($BibtexBibDir . $bib_file))
        if (file_exists($bib_file))
        {
//            $f = fopen($BibtexBibDir . $bib_file, "r");
            $f = fopen($bib_file, "r");
            $bib_file_string = "";

            if ($f)
            {
                while (!feof($f))
                {
                    $bib_file_string = $bib_file_string . fgets($f, 1024);
                }

                $bib_file_string = preg_replace("/\n/", "", $bib_file_string);

                ParseBib($bib_file, $bib_file_string);

//                return true;
                  return $BibEntries;
            }
            return false;
        }
//    }
}

//$UploadExts['bib'] = 'text/plain';

?>
