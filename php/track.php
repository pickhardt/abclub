<script type='text/javascript'>
  window['_gaq'] = window['_gaq'] || [];
  window['optimizely'] = window['optimizely'] || [];
  
<?php
  foreach (ABClub::activeExperiments() as $name => $experiment) {
    $optimizelyData = $experiment->getOptimizelyTracking();
?>
  window['_gaq'].push(['_trackEvent', 'abtest', '<?php echo $experiment->name ?>', '<?php echo $experiment->getBucket() ?>']);
  
  <% if ($experiment->shouldTrackWithOptimizely()) { %>
    window['optimizely'].push(["bucketVisitor", "<?php echo $experiment->getOptimizelyExperimentId() ?>", "<?php echo $experiment->getOptimizelyVariationId() ?>"]);
    window['optimizely'].push(["activate", "<?php echo $experiment->getOptimizelyExperimentId() ?>"]);
  <% } %>
<?php
  }
?>
</script>
