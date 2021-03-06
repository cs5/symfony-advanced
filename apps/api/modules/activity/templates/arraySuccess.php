<?php

$ac = array();

foreach ($activityData as $activity)
{
  $acEntity = sa_api_activity($activity);

  $replies = $activity->getReplies();
  if (0 !== count($replies))
  {
    $acEntity['replies'] = array();

    foreach ($replies as $reply)
    {
      $acEntity['replies'][] = sa_api_activity($reply);
    }
  }

  $ac[] = $acEntity;
}

return array(
  'status' => 'success',
  'data' => $ac,
);
