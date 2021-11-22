import "./plyr.css";

import $ from "jquery";
import Plyr from "plyr";

$(".video-default video").each((i, el) => {
  new Plyr($(el)[0], {
    controls: [
      "play-large",
      "play",
      "progress",
      "current-time",
      "mute",
      "volume",
      "fullscreen",
    ],
  });
});
