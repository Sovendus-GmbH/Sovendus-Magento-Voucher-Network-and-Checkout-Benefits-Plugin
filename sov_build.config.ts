import type { BuildConfig } from "sovendus-builder";

const buildConfig: BuildConfig = {
  filesToCompile: [
    {
      input: "SovendusApp/admin-frontend/frontend_react_loader.ts",
      output: "SovendusApp/view/adminhtml/web/js/frontend_react_loader.js",
      options: { type: "react-tailwind" },
    },
    {
      input:
        "node_modules/sovendus-plugins-commons/page-scripts/landing-page/sovendus-page.ts",
      output: "SovendusApp/view/frontend/web/js/sovendus-page.js",
      options: { type: "vanilla" },
    },
    {
      input:
        "node_modules/sovendus-plugins-commons/page-scripts/thankyou-page/thankyou-page.ts",
      output: "SovendusApp/view/frontend/web/js/thankyou-page.js",
      options: { type: "vanilla" },
    },
  ],
};

export default buildConfig;
