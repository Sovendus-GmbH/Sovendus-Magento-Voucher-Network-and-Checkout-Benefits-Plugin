import { SovendusSettings } from "../sovendus-plugins-commons/admin-frontend/sovendus-app-settings";
import React from "react";
import ReactDOM from "react-dom";
import type { SovendusFormDataType } from "../sovendus-plugins-commons/admin-frontend/sovendus-app-types";

document.addEventListener("DOMContentLoaded", () => {
  const currentSettings = sovendusSettings.settings as SovendusFormDataType;
  const saveUrl = ajaxurl as string;
  const containerId = "sovendus-settings-container";
  const container = document.getElementById(containerId);
  if (!container) {
    console.error(`Container with id ${containerId} not found`);
    return;
  }
  const shadowRoot = container.attachShadow({ mode: "open" });
  const reactRoot = document.createElement("div");
  shadowRoot.appendChild(reactRoot);

  const handleSettingsUpdate = async (
    updatedSettings: SovendusFormDataType
  ): Promise<SovendusFormDataType> => {
    console.log("Attempting to save settings...");

    try {
      const response = await fetch(saveUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          action: "wc_sovendus_save_settings",
          settings: updatedSettings,
        }),
      });

      console.log("Response status:", response.status);
      const responseText = await response.text();
      console.log("Raw response:", responseText);

      const data = JSON.parse(responseText);
      if (data.success) {
        console.log("Settings saved successfully");
        return updatedSettings;
      }

      throw new Error(data.data || "Unknown server error");
    } catch (error) {
      console.error("Save failed:", error);
      throw error;
    }
  };

  ReactDOM.render(
    React.createElement(SovendusSettings, {
      saveSettings: handleSettingsUpdate,
      currentStoredSettings: currentSettings,
    }),
    reactRoot
  );
});
