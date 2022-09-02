import "./plyr.css";

import $ from "jquery";
import Plyr from "plyr";

$(".youtube-default").each((index, el) => {
  const data = window[$(el).data("component")];
  const hasAutoplay = data.autoplay === "enabled";

  new Plyr($(el).find(".plyr__video-embed")[0], {
    autoplay: hasAutoplay,
    muted: hasAutoplay,
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
