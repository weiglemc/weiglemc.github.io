---
layout: archive
title: "Publications"
permalink: /publications/
author_profile: true
---

{% if page.author and site.data.authors[page.author] %}
  {% assign author = site.data.authors[page.author] %}{% else %}{% assign author = site.author %}
{% endif %}

{% if author.googlescholar %}
  You can also find my publications on <a href="{{author.googlescholar}}" target="_blank">my Google Scholar profile</a>.
{% endif %}

{% include base_path %}

[Static Publications List](../publications-static/)

*BibTeX links don't work yet...*

{% for post in site.publications reversed %}
  {% if post.type == "year" %}
    {% include archive-single.html %}
  {% endif %}
{% endfor %}
