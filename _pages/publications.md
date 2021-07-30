---
layout: archive
title: "Publications"
permalink: /publications/
author_profile: true
redirect_from: 
  - /pubs/
  - /publications/pubsbyyear/
---

{% if page.author and site.data.authors[page.author] %}
  {% assign author = site.data.authors[page.author] %}{% else %}{% assign author = site.author %}
{% endif %}

{% if author.googlescholar %}
  You can also find my publications on <a href="{{author.googlescholar}}" target="_blank">my Google Scholar profile</a>.
{% endif %}

{% include base_path %}

[Sorted by Type](/publications/pubsbytype), [Award Publications](/publications/pubs-awards), [External Publication Lists](/publications/lists)

{% for post in site.publications reversed %}
  {% if post.type == "year" %}
    {% include archive-single.html %}
  {% endif %}
{% endfor %}
