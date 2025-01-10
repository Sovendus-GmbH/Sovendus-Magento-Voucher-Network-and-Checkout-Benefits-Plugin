import { SovendusSettings } from "../sovendus-plugins-commons/admin-frontend/sovendus-app-settings";
import React from "react";
import ReactDOM from "react-dom";
import type { SovendusFormDataType } from "../sovendus-plugins-commons/admin-frontend/sovendus-app-types";

document.addEventListener("DOMContentLoaded", () => {
  const containerId = "sovendus-settings-container";
  const container = document.getElementById(containerId);
  const formKey =
    document.querySelector<HTMLInputElement>('[name="form_key"]')?.value;

  if (!container || !formKey) {
    console.error("Required elements not found");
    return;
  }

  const settings = JSON.parse(container.dataset.settings || "{}");
  const saveUrl = container.dataset.saveUrl;

  const shadowRoot = container.attachShadow({ mode: "open" });
  const reactRoot = document.createElement("div");
  shadowRoot.appendChild(reactRoot);

  const handleSettingsUpdate = async (
    updatedSettings: SovendusFormDataType
  ): Promise<SovendusFormDataType> => {
    try {
      const response = await fetch(saveUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
          form_key: formKey,
          settings: updatedSettings,
        }),
      });

      const data = await response.json();
      if (data.success) {
        return updatedSettings;
      }
      throw new Error(data.message || "Save failed");
    } catch (error) {
      console.error("Save failed:", error);
      throw error;
    }
  };

  ReactDOM.render(
    React.createElement(SovendusSettings, {
      saveSettings: handleSettingsUpdate,
      currentStoredSettings: settings,
    }),
    reactRoot
  );
});
