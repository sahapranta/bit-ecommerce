// Imports
import * as bootstrap from "bootstrap";
import SimpleBar from "simplebar";
import Helpers from "./oneui/modules/helpers";

// Assignments
window.bootstrap = bootstrap;
window.SimpleBar = SimpleBar;

function onLoad(fn) {
  if (document.readyState != "loading") {
    fn();
  } else {
    document.addEventListener("DOMContentLoaded", fn);
  }
}


onLoad(function () {
  Helpers.run([
    "bs-tooltip",
    "bs-popover",
    "one-toggle-class",
    "one-year-copy",
    "one-ripple",
  ]);
});
