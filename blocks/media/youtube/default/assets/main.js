import "./plyr.css";

import $ from "jquery";
import Plyr from "plyr";

$(".youtube-default").each((index, el) => {
  const data = window[$(el).data("component")];

  // Video
  const youtubeParser = (url) => {
    const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    const match = url.match(regExp);
    return match && match[7].length === 11 ? match[7] : false;
  };

  const video = `${youtubeParser($(el).find("iframe").attr("data-video"))}?amp;iv_load_policy=3&amp;listType=user_uploads&amp;controls=0&amp;fs=0&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1`;
  $(el).find("iframe").attr("src", `https://www.youtube.com/embed/${video}`);

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
