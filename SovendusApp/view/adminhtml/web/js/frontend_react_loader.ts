import React from "react";
import ReactDOM from "react-dom/client";
import {
  loggerError,
  loggerInfo,
  SovendusBackendForm,
} from "sovendus-integration-settings-ui";
import type { SovendusAppSettings } from "sovendus-integration-types";

async function loadSetting(): Promise<void> {
  const container = createRootElement();
  if (!container) {
    return;
  }
  const reactRoot = ReactDOM.createRoot(container);

  try {
    const currentStoredSettings = await getSettings(settingsUrl);
    reactRoot.render(
      React.createElement(SovendusBackendForm, {
        saveSettings,
        currentStoredSettings,
        zoomedVersion: true,
        callSaveOnLoad: false,
      }),
    );
  } catch (error) {
    loggerError("Failed to fetch settings:", error);
  }
}

const getSettings = async (
  settingsUrl: string,
): Promise<SovendusAppSettings> => {
  const response = await fetch(settingsUrl);
  try {
    const currentSettingsJson = (await response.json()) as string;
    loggerInfo("Current settings:", currentSettingsJson);
    const currentSettings = JSON.parse(
      currentSettingsJson,
    ) as SovendusAppSettings;

    if (typeof currentSettings !== "object") {
      loggerInfo("Current settings:", typeof currentSettings, currentSettings);
      throw new Error("Invalid settings format received from server");
    }
    return currentSettings || {};
  } catch (error) {
    loggerError("Failed to parse settings:", error);
  }
  return {} as SovendusAppSettings;
};

const settingsUrl = "/rest/V1/sovendus/config";
const saveSettings = async (
  updatedSettings: SovendusAppSettings,
): Promise<SovendusAppSettings> => {
  try {
    const response = await fetch(settingsUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "same-origin",
      body: JSON.stringify({
        config: JSON.stringify(updatedSettings),
      }),
    });

    const responseText = await response.text();
    if (response.ok) {
      return updatedSettings;
    }
    throw new Error(responseText);
  } catch (error) {
    loggerError("Save failed:", error);
    throw error;
  }
};

function createRootElement(): HTMLDivElement | undefined {
  const containerId = "container";
  const container = document.getElementById(containerId) as HTMLDivElement;

  if (!container) {
    loggerError(`Container with id ${containerId} not found`);
    return;
  }
  // Hide the original settings form and save button
  (container.firstElementChild as HTMLElement).style.setProperty(
    "display",
    "none",
  );
  (
    document.querySelector("button.save") as HTMLButtonElement
  ).style.setProperty("display", "none");

  const settingsContainer = document.createElement("div");
  settingsContainer.id = "sovendus-settings-container";

  container.appendChild(settingsContainer);
  return settingsContainer;
}

void loadSetting();
