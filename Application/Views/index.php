<div class="well">
    <h2><?php echo SITETITLE . ' &mdash; ' . $data['lng']->get('index'); ?></h2>

    <p><?php echo $data['lng']->get('index_welcome'); ?></p>
</div>

<div class="well">
    <h2><?php echo SITETITLE . ' &mdash; ' . $data['lng_sec']['second']; ?></h2>

    <p><?php echo $data['lng_sec']['second_welcome']; ?></p>
</div>

<ul class="nav nav-pills">
    <li role="presentation" class="active"><a href="/">Index</a></li>
    <li role="presentation"><a href="/page">Page</a></li>
    <li role="presentation"><a href="/page/another">Another Page</a></li>
</ul>
