<?php

/**
 * This script reads the generated coverage xml report and throws an exception if
 * the coverage percentage is below 100.
 */

const XML_REPORT_FILE = __DIR__.'/coverage/xml/index.xml';

$file = simplexml_load_string(file_get_contents(XML_REPORT_FILE));

$percent = (float)$file->project->directory[0]->totals[0]->lines['percent']->__toString();

if ($percent < 100) {
    echo "\n\n--------------------------------------------------\n\n";
    echo "   Code coverage (".$percent."%) is below min 100%!\n\n";
    echo "--------------------------------------------------\n\n";

    exit(1);
}

exit(0);

