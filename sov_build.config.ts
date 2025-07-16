import type { BuildConfig } from "sovendus-builder";

const buildConfig: BuildConfig = {
  filesToCompile: [
    {
      sovOptions: {
        input: "./SovendusApp/view/adminhtml/web/js/frontend_react_loader.ts",
        output: "./SovendusApp/view/adminhtml/web/js/frontend_react_loader.js",
        type: "react-tailwind",
      },
    },
    {
      sovOptions: {
        input: "./SovendusApp/view/frontend/web/ts/sovendus-page.ts",
        output: "./SovendusApp/view/frontend/web/js/sovendus-page.js",
        type: "vanilla",
      },
    },
    {
      sovOptions: {
        input: "./SovendusApp/view/frontend/web/ts/thankyou-page.ts",
        output: "./SovendusApp/view/frontend/web/js/thankyou-page.js",
        type: "vanilla",
      },
    },
  ],
};

export default buildConfig;
