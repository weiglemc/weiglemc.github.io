<?php
/* bib2md-students.php
* Generate Jekyll-compatable .md file from BibTeX file
*   - single file with student thesis and dissertations
*   - I copy the result into my students.md page that lists all current/former students
* Based on BibtexRef plugin for PmWiki, https://www.pmwiki.org/wiki/Cookbook/BibtexRef
*   - modified to generate Markdown - Michele Weigle, July 2021 
* Example commands (from bibtexref3-md.php):
    $reftag = "jones-websci21";
    CompleteBibEntry($bibTexFile, $reftag);
    BibSummary($bibTexFile, $reftag);
    BibCite($bibTexFile, $reftag);
*/
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
   
    include 'bibtexref3-md.php';				
   
    $bibTexFile = 'mweigle-thesis.bib';
    $outputDir = '.';
    
    $outfile = "student-thesis.md";
    $fp = fopen("$outputDir/$outfile", "w") or die("Unable to open file!");

    // generate one line per student
    $tags = array('aturban-phd20', 'kelly-phd19', 'alkwai-phd19', 'almaksousy-phd18',
    'mohrehkesh-phd15', 'almalag-phd13', 'arbabi-phd11', 'ibrahim-phd11', 'yan-phd10',
    'berlin-ms18', 'kelly-ms12', 'padia-ms12', 'adurthi-ms06', 'sharma-ms06');

    foreach ($tags as $tag) {
        // BibSummary($bibTexFile, $reftag);
        $paper_string = BibSummary($bibTexFile, $tag, false);
        fwrite($fp, $paper_string);
        fwrite($fp, "\n\n");
    }

    // note that BibTeX link will still be in the entry and may need to be removed
  
    fclose($fp);
?>

