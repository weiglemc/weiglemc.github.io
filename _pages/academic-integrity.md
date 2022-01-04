---
permalink: /academic-integrity/
title: "Academic Integrity"
---

*Page rebuilt from http://web.archive.org/web/20210413115446id_/https://graduate.cs.odu.edu/resources/academic-integrity/*

ODU and the Department of Computer Science take academic integrity seriously. See [Monarch Citizenship](https://www.odu.edu/about/monarchcitizenship) for the ODU Honor Code and Honor Pledge.

Many ODU-CS courses follow these guidelines:
* Unless explicitly specified, all assignments are to be completed on your own.
* Unless explicitly specified, no sharing of code is allowed. This includes discussion about the design of a programming assignment solution.
* Unless explicitly specified, written assignments are expected to be in your own words.
* Giving unauthorized assistance is just as much of an offense as receiving unauthorized assistance.

See ODU’s official [Code of Student Content](https://www.odu.edu/content/dam/odu/offices/bov/policies/1500/BOV1530.pdf) (pdf) for more information on the specific university policies and procedures. Here are a couple of important notes from that document:
* “Students found responsible for an academic integrity violation will normally have a notation placed on the student’s academic transcript.”
* “Faculty who suspect a graduate student may have violated one or more standards of Academic Integrity should consult with the [Office of Student Conduct & Academic Integrity](https://www.odu.edu/oscai), as *graduate students are normally reviewed for suspension or expulsion, even for a first Academic integrity violation*.”

***You have been warned. You are responsible for knowing the rules or asking for clarification.***

## What is Cheating/Plagiarism?

Basically, it is submitting work that is not your own.
* If you use someone else’s ideas, you must cite the source material.
* If you use someone else’s words or code, you must mark them as a “quotation” and cite the source material.

### For Coding

When coding, cheating/plagiarism includes
* copying or sharing source code for assignments
* posting details of course assignments, projects, or tests at online Forums, Bulletin Boards, Homework sites, etc., soliciting help
* obtaining solutions from the Internet and submitting them as your own — if you are allowed to use snippets of code obtained from the Internet, you must cite them in your code comments (see below for examples)

Examples of citing sources in code comments

Using someone else’s ideas (but not directly copying code)
```c++
double x = 23.0;
double xsqrt = sqrt(x);
// Search algorithm based upon code by S Zeil at
// https://www.cs.odu.edu/~zeil/cs361/latest/Public/functionAnalysis/index.html#orderedsequentialsearch
int loc = 0;
while (loc < arraySize && numbers[loc] < xsqrt)
```

Using someone else’s code
```c++
double x = 23.0;
double xsqrt = sqrt(x);
// Begin quoted code from  S Zeil at
// https://www.cs.odu.edu/~zeil/cs361/latest/Public/functionAnalysis/index.html#orderedsequentialsearch
int loc = 0;
while (loc < listLength && list[loc] < searchItem)
{
     ++loc;
}
// End quoted code
```

Tips to avoid cheating (even inadvertently):
* don’t start at the last minute.
* don’t sit next to each other in the lab and talk about the assignment while you’re working on it.
* ask the instructor if you’re stuck — which means that you can’t start at the last minute.

### For Writing

Determining plagiarism for writing can be more complicated than for coding. The same basic principle applies though — don’t try to pass off something as your work that is not your own.

Here are some examples of plagiarism:
* copying material from a source text without proper acknowledgment
* copying material from a source text, supplying proper acknowledgment, but leaving out quotation marks

“In your own words” means that the text should be your own and not a paraphrase of others’ work. Just because someone wrote it better than you would have does not make it acceptable to copy their words.

Understand also that you will generally be graded upon your own contribution. Finding the perfect quotation or string of quotations to address a topic may still earn you a zero if you add no thoughts or ideas of your own. If you didn’t write it, you don’t get the grade for it!

## Resources

You are strongly encouraged to read through the following resources to get a better understanding of plagiarism and how to avoid it.
* Plagiarism information from ODU – https://wp.odu.edu/plagiarism/
* [12 types of plagiarism](https://www.myperfectwords.com/blog/general/types-of-plagiarism)
* [“What is Plagiarism?”](http://www.plagiarism.org/article/what-is-plagiarism), from plagiarism.org
* [“Preventing Plagiarism When Writing”](http://www.plagiarism.org/article/preventing-plagiarism-when-writing), from plagiarism.org
* examples of plagiarism and tips on avoiding it – <https://writing.wisc.edu/Handbook/QPA_paraphrase.html>, from the University of Wisconsin
