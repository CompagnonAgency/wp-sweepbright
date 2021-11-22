<?php if (WP_Wrapper::get('questions', $component, $args)) : ?>
  <ul class="faq-default">
    <?php foreach (WP_Wrapper::get('questions', $component, $args) as $index => $question) : ?>
      <li class="rounded">
        <a href=" #" class="rounded-t question text-primary">
          <p><?= WP_Wrapper::get('title', $component, $args, $question); ?></p>
        </a>
        <div class="answer">
          <div class="post">
            <?= WP_Wrapper::get('description', $component, $args, $question); ?>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
