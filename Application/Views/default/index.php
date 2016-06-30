<div class="well">
    <h2><?php echo SITETITLE . ' &mdash; ' . $data['lng']->get('index'); ?></h2>

    <p><?php echo $data['lng']->get('index_welcome'); ?></p>
</div>

<div class="well">
    <h2><?php echo SITETITLE . ' &mdash; ' . $data['lng']->get('second'); ?></h2>

    <p><?php echo $data['lng']->get('second_welcome'); ?></p>
</div>

<div class="well">
    <p>UUID v4: <?php echo $data['uuid']; ?></p>
</div>

<ul class="nav nav-pills">
    <li role="presentation" class="active"><a href="/">Index</a></li>
    <li role="presentation"><a href="/page">Page</a></li>
    <li role="presentation"><a href="/page/another">Another Page</a></li>
</ul>
