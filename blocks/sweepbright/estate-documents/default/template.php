<div id="documents">
  <div class="mb-8 post">
    <h2><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['heading']; ?></h2>
  </div>

  <div class="space-y-6">
    <div>
      <div class="mb-5 post">
        <h5><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['plans']; ?></h5>
      </div>
      <?php if (get_field('features')['plans'] && count(get_field('features')['plans']) > 0) : ?>
        <ul class="space-y-4">
          <?php foreach (get_field('features')['plans'] as $plan) : ?>
            <li>
              <a href="<?= $plan['file']['url']; ?>" target="_blank" class="font-semibold">
                <i class="mt-1 mr-3 fal fa-file-pdf"></i>
                <?php if ($plan['description']) : ?>
                  <?= $plan['description']; ?>
                <?php else : ?>
                  <?= $plan['file']['filename']; ?>
                <?php endif; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else : ?>
        -
      <?php endif; ?>
    </div>

    <div>
      <div class="mb-5 post">
        <h5><?= WP_Wrapper::get('locale', $component, $args)[WP_Wrapper::lang()]['documents']; ?></h5>
      </div>
      <?php if (get_field('features')['documents'] && count(get_field('features')['documents']) > 0) : ?>
        <ul class="space-y-4">
          <?php foreach (get_field('features')['documents'] as $document) : ?>
            <li>
              <a href="<?= $document['file']['url']; ?>" target="_blank" class="font-semibold">
                <i class="mt-1 mr-3 fal fa-file-pdf"></i>
                <?php if ($document['description']) : ?>
                  <?= $document['description']; ?>
                <?php else : ?>
                  <?= $document['file']['filename']; ?>
                <?php endif; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else : ?>
        -
      <?php endif; ?>
    </div>
  </div>
</div>
