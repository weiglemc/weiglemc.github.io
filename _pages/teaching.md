---
layout: archive
title: "Teaching"
permalink: /teaching/
author_profile: true
---

[Static Teaching Page](../teaching-static/)

{% include base_path %}

{% for post in site.teaching reversed %}
  {% if post.type == "semester" %}
    {% include archive-single.html %}
  {%endif %}
{% endfor %}
