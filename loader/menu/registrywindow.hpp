#pragma once
#include <iostream>
#include <windows.h>
#include <thread>
#include "ImGui/imgui.h"
#include "ImGui/imgui_impl_dx9.h"
#include "ImGui/imgui_internal.h"

#include "resources/font.hpp"
#include "ImGui/imgui_internal.h"
#include "../globals.hpp"

#include <d3dx9.h>
#include <d3d9.h>
#include <cmath>
#include <random>

#pragma comment(lib, "d3d9.lib")
#pragma comment(lib, "d3dx9.lib")
#pragma comment(lib,"dxguid.lib")

#define rgba_to_float(r,g,b,a) (float)r/255.0f, (float)g/255.0f, (float)b/255.0f, (float)a/255.0f

ImFont* Normal = nullptr;
ImFont* Main = nullptr;
ImFont* Notification = nullptr;
ImFont* Medium = nullptr;

ATOM RegMyWindowClass(HINSTANCE, LPCTSTR);
static LPDIRECT3DDEVICE9        g_pd3dDevice = NULL;
static D3DPRESENT_PARAMETERS    g_d3dpp;
extern LRESULT ImGui_ImplDX9_WndProcHandler(HWND hWnd, UINT msg, WPARAM wParam, LPARAM lParam);
LRESULT CALLBACK WndProc(HWND, UINT, WPARAM, LPARAM);
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam) {
	if (ImGui_ImplDX9_WndProcHandler(hWnd, message, wParam, lParam))
		return true;

	switch (message) {
	case WM_SIZE:
		if (g_pd3dDevice != NULL && wParam != SIZE_MINIMIZED)
		{
			ImGui_ImplDX9_InvalidateDeviceObjects();
			g_d3dpp.BackBufferWidth = LOWORD(lParam);
			g_d3dpp.BackBufferHeight = HIWORD(lParam);
			HRESULT hr = g_pd3dDevice->Reset(&g_d3dpp);
			if (hr == D3DERR_INVALIDCALL) IM_ASSERT(0);
			ImGui_ImplDX9_CreateDeviceObjects();
		}
		return NULL;

	case WM_SYSCOMMAND:
		if ((wParam & 0xfff0) == SC_KEYMENU) return NULL;
		break;
	case WM_DESTROY:
		PostQuitMessage(0);
		return NULL;
	}
	return DefWindowProc(hWnd, message, wParam, lParam);
}

ATOM RegMyWindowClass(HINSTANCE hInst, LPCTSTR lpzClassName) {
	WNDCLASS wcWindowClass = { 0 };
	wcWindowClass.lpfnWndProc = (WNDPROC)WndProc;
	wcWindowClass.style = CS_HREDRAW | CS_VREDRAW;
	wcWindowClass.hInstance = hInst;
	wcWindowClass.lpszClassName = lpzClassName;
	wcWindowClass.hCursor = LoadCursor(NULL, IDC_ARROW);
	wcWindowClass.hbrBackground = (HBRUSH)COLOR_APPWORKSPACE;
	return RegisterClass(&wcWindowClass);
}

void Style()
{
	ImGuiStyle* style = &ImGui::GetStyle();
	auto& io = ImGui::GetIO();

	style->Alpha = 1.0f;
	style->WindowPadding = ImVec2(7.f, 7.f);
	style->WindowMinSize = ImVec2(32, 32);
	style->WindowRounding = 0.0f;
	style->WindowTitleAlign = ImVec2(0.5f, 0.5f);
	style->ChildWindowRounding = 0.f;
	style->FramePadding = ImVec2(3, 3);
	style->FrameRounding = 3.0f;
	style->ItemSpacing = ImVec2(8, 4);
	style->ItemInnerSpacing = ImVec2(8, 0);
	style->TouchExtraPadding = ImVec2(0, 0);
	style->IndentSpacing = 21.0f;
	style->ColumnsMinSpacing = 3.0f;
	style->ScrollbarSize = 12.0f;
	style->ScrollbarRounding = 0.0f;
	style->GrabMinSize = 5.0f;
	style->GrabRounding = 0.0f;
	style->ButtonTextAlign = ImVec2(0.5f, 0.4f);
	style->DisplayWindowPadding = ImVec2(22, 22);
	style->DisplaySafeAreaPadding = ImVec2(4, 4);
	style->AntiAliasedLines = true;
	style->AntiAliasedShapes = true;
	style->CurveTessellationTol = 1.25f;


	float g_fMColor[4] = { 0.21f, 0.21f, 0.21f, 1.0f };
	float g_fBColor[4] = { 0.1f, 0.1f, 0.1f, 1.0f };
	float g_fTColor[4] = { 1.f, 1.f, 1.f, 1.0f };
	float menucolor[4] = { 0.49f, 0.66f, 0.035f, 1.0f };
	auto bColor = g_fBColor;
	auto mColor = g_fMColor;
	auto tColor = g_fTColor;
	ImColor mainColor = ImColor(int(mColor[0] * 255.0f), int(mColor[1] * 255.0f), int(mColor[2] * 255.0f), 255);
	ImColor bodyColor = ImColor(int(bColor[0] * 255.0f), int(bColor[1] * 255.0f), int(bColor[2] * 255.0f), 255);
	ImColor fontColor = ImColor(int(tColor[0] * 255.0f), int(tColor[1] * 255.0f), int(tColor[2] * 255.0f), 255);
	ImColor menuColor = ImColor(int(menucolor[0] * 255.0f), int(menucolor[1] * 255.0f), int(menucolor[2] * 255.0f), 210);

	ImVec4 mainColorHovered = ImVec4(mainColor.Value.x + 0.1f, mainColor.Value.y + 0.1f, mainColor.Value.z + 0.1f, mainColor.Value.w);
	ImVec4 mainColorActive = ImVec4(mainColor.Value.x + 0.2f, mainColor.Value.y + 0.2f, mainColor.Value.z + 0.2f, mainColor.Value.w);
	ImVec4 menubarColor = ImVec4(bodyColor.Value.x, bodyColor.Value.y, bodyColor.Value.z, bodyColor.Value.w - 0.8f);
	ImVec4 frameBgColor = ImVec4(bodyColor.Value.x, bodyColor.Value.y, bodyColor.Value.z, bodyColor.Value.w + .1f);
	ImVec4 tooltipBgColor = ImVec4(bodyColor.Value.x, bodyColor.Value.y, bodyColor.Value.z, bodyColor.Value.w + .05f);

	style->Colors[ImGuiCol_Text] = ImVec4(rgba_to_float(218.f, 218.f, 218.f, 255.f));
	style->Colors[ImGuiCol_TextDisabled] = ImVec4(rgba_to_float(218.f, 218.f, 218.f, 255.f));
	style->Colors[ImGuiCol_WindowBg] = ImVec4(rgba_to_float(18.f, 18.f, 18.f, 255.f));
	style->Colors[ImGuiCol_ChildWindowBg] = ImVec4(rgba_to_float(24.f, 24.f, 24.f, 210.f));
	style->Colors[ImGuiCol_Border] = ImVec4(rgba_to_float(70.f, 70.f, 70.f, 175.f));
	style->Colors[ImGuiCol_BorderShadow] = ImVec4(rgba_to_float(0.f, 0.f, 0.f, 5.f));
	style->Colors[ImGuiCol_FrameBg] = ImVec4(rgba_to_float(32.f, 32.f, 32.f, 170.f));
	style->Colors[ImGuiCol_FrameBgHovered] = ImVec4(rgba_to_float(32.f, 32.f, 32.f, 150.f));
	style->Colors[ImGuiCol_FrameBgActive] = ImVec4(rgba_to_float(32.f, 32.f, 32.f, 150.f));
	style->Colors[ImGuiCol_TitleBg] = ImVec4(0.20f, 0.22f, 0.27f, 1.00f);
	style->Colors[ImGuiCol_TitleBgCollapsed] = ImVec4(0.20f, 0.22f, 0.27f, 1.00f);
	style->Colors[ImGuiCol_TitleBgActive] = ImVec4(0.20f, 0.22f, 0.27f, 1.00f);
	style->Colors[ImGuiCol_MenuBarBg] = ImVec4(0.20f, 0.22f, 0.27f, 0.47f);
	style->Colors[ImGuiCol_ScrollbarBg] = ImVec4(rgba_to_float(17.f, 17.f, 17.f, 210.f));
	style->Colors[ImGuiCol_ScrollbarGrab] = mainColor;
	style->Colors[ImGuiCol_ScrollbarGrabHovered] = mainColorHovered;
	style->Colors[ImGuiCol_ScrollbarGrabActive] = mainColorActive;
	style->Colors[ImGuiCol_ComboBg] = mainColor;
	style->Colors[ImGuiCol_CheckMark] = ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f));
	style->Colors[ImGuiCol_SliderGrab] = menuColor;
	style->Colors[ImGuiCol_SliderGrabActive] = menuColor;
	style->Colors[ImGuiCol_Button] = ImVec4(rgba_to_float(32.f, 32.f, 32.f, 170.f));
	style->Colors[ImGuiCol_ButtonHovered] = ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f));
	style->Colors[ImGuiCol_ButtonActive] = ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f));
	style->Colors[ImGuiCol_Header] = ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f));
	style->Colors[ImGuiCol_HeaderHovered] = ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f));
	style->Colors[ImGuiCol_HeaderActive] = ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f));
	style->Colors[ImGuiCol_Column] = ImVec4(0.14f, 0.16f, 0.19f, 1.00f);
	style->Colors[ImGuiCol_ColumnHovered] = ImVec4(0.92f, 0.18f, 0.29f, 0.78f);
	style->Colors[ImGuiCol_ColumnActive] = ImVec4(0.92f, 0.18f, 0.29f, 1.00f);
	style->Colors[ImGuiCol_ResizeGrip] = ImVec4(0.47f, 0.77f, 0.83f, 0.04f);
	style->Colors[ImGuiCol_ResizeGripHovered] = ImVec4(0.92f, 0.18f, 0.29f, 0.78f);
	style->Colors[ImGuiCol_ResizeGripActive] = ImVec4(0.92f, 0.18f, 0.29f, 1.00f);
	style->Colors[ImGuiCol_CloseButton] = ImVec4(rgba_to_float(0.f, 0.f, 0.f, 0.f));
	style->Colors[ImGuiCol_CloseButtonHovered] = ImVec4(rgba_to_float(0.f, 0.f, 0.f, 0.f));
	style->Colors[ImGuiCol_CloseButtonActive] = ImVec4(rgba_to_float(0.f, 0.f, 0.f, 0.f));
	style->Colors[ImGuiCol_PlotLines] = ImVec4(0.86f, 0.93f, 0.89f, 0.63f);
	style->Colors[ImGuiCol_PlotLinesHovered] = ImVec4(0.92f, 0.18f, 0.29f, 1.00f);
	style->Colors[ImGuiCol_PlotHistogram] = ImVec4(0.86f, 0.93f, 0.89f, 0.63f);
	style->Colors[ImGuiCol_PlotHistogramHovered] = ImVec4(0.92f, 0.18f, 0.29f, 1.00f);
	style->Colors[ImGuiCol_TextSelectedBg] = ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f));
	style->Colors[ImGuiCol_PopupBg] = ImVec4(rgba_to_float(17.f, 17.f, 17.f, 210.f));
	style->Colors[ImGuiCol_ModalWindowDarkening] = ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f));

	ImFontConfig font; font.FontDataOwnedByAtlas = false;

	Normal = io.Fonts->AddFontFromMemoryTTF(irobotolight, sizeof(irobotolight), 15.0f, &font, io.Fonts->GetGlyphRangesCyrillic());
	Main = io.Fonts->AddFontFromMemoryTTF(irobotolight, sizeof(irobotolight), 30.0f, &font, io.Fonts->GetGlyphRangesCyrillic());
	Notification = io.Fonts->AddFontFromMemoryTTF(irobotolight, sizeof(irobotolight), 12.5f, &font, io.Fonts->GetGlyphRangesCyrillic());
	Medium = io.Fonts->AddFontFromMemoryTTF(irobotomedium, sizeof(irobotomedium), 25.0f, &font, io.Fonts->GetGlyphRangesCyrillic());
		
	io.IniFilename = nullptr, io.LogFilename = nullptr;
}


namespace notification {

	bool loading_circle(const char* label, float radius, int thickness, const ImU32& color) 
	{
		ImGuiWindow* window = ImGui::GetCurrentWindow();
		if (window->SkipItems)
			return false;

		ImGuiContext& g = *GImGui;
		const ImGuiStyle& style = g.Style;
		const ImGuiID id = window->GetID(label);

		ImVec2 pos = window->DC.CursorPos;
		ImVec2 size((radius) * 2, (radius + style.FramePadding.y) * 2);

		const ImRect bb(pos, ImVec2(pos.x + size.x, pos.y + size.y));
		ImGui::ItemSize(bb, style.FramePadding.y);
		if (!ImGui::ItemAdd(bb, &id))
			return false;

		// Render
		window->DrawList->PathClear();

		int num_segments = 30;
		int start = abs(ImSin(g.Time * 1.8f) * (num_segments - 5));

		const float a_min = IM_PI * 2.0f * ((float)start) / (float)num_segments;
		const float a_max = IM_PI * 2.0f * ((float)num_segments - 3) / (float)num_segments;

		const ImVec2 centre = ImVec2(pos.x + radius, pos.y + radius + style.FramePadding.y);

		for (int i = 0; i < num_segments; i++) {
			const float a = a_min + ((float)i / (float)num_segments) * (a_max - a_min);
			window->DrawList->PathLineTo(ImVec2(centre.x + ImCos(a + g.Time * 8) * radius,
				centre.y + ImSin(a + g.Time * 8) * radius));
		}

		window->DrawList->PathStroke(color, false, thickness);
	}

	long getMils() {
		auto duration = std::chrono::system_clock::now().time_since_epoch();

		return std::chrono::duration_cast<std::chrono::milliseconds>(duration).count();
	}

	bool reverse = false;
	int offset = 0;
	bool show_popup = false;

	void notify(const char* text, int onScreenMils, bool* done) 
	{
		if (!done)
			show_popup = true;

		ImGuiIO& io = ImGui::GetIO();

		static long oldTime = -1;
		ImGui::SetNextWindowPos(ImVec2(0.f, 0.f));
		ImGui::Begin(xorstr_("##notificationbackground"), &show_popup, ImVec2(g_globals.client_side.window_settings.width, g_globals.client_side.window_settings.height), 0.9f, ImGuiWindowFlags_NoResize | ImGuiWindowFlags_NoScrollbar | ImGuiWindowFlags_NoTitleBar);
		{
			ImGui::SetCursorPos(ImVec2(ImGui::GetWindowWidth() / 5.5f, (ImGui::GetWindowHeight() / 2.f - 25.f)));
			ImGui::PushStyleColor(ImGuiCol_ChildWindowBg, ImVec4(rgba_to_float(24.f, 24.f, 24.f, 255.f)));
			ImGui::BeginChild(xorstr_("##mainnotify"), ImVec2(280.f, 50.f), false, ImGuiWindowFlags_NoScrollbar | ImGuiWindowFlags_NoCollapse | ImGuiWindowFlags_NoResize | ImGuiWindowFlags_NoTitleBar | ImGuiWindowFlags_NoScrollWithMouse | ImGuiWindowFlags_NoSavedSettings);
			{
				long currentTime_ms = notification::getMils();
				ImVec2 txtSz = ImGui::CalcTextSize(text);			
				ImGui::SetCursorPos(ImVec2((ImGui::GetWindowWidth() / 2.f) - 11.f, 5.f)); loading_circle("##loadingnotify", 8, 2, ImGui::GetColorU32(ImGuiCol_ButtonHovered));
				if (!reverse)
				{				
					if (offset < ImGui::GetWindowWidth()) {

						offset += (ImGui::GetWindowWidth()) * ((1500.0f / ImGui::GetIO().Framerate) / 500);
					}

					if (offset >= ImGui::GetWindowWidth() && oldTime == -1)
					{

						oldTime = currentTime_ms;
					}
				}
				if (currentTime_ms - oldTime >= onScreenMils && oldTime != -1) // close after x mils
					reverse = true;                                                                                                                                                                                                      
				if (reverse)
				{
					ImGui::PushFont(Notification);
					ImGui::SetCursorPosX((ImGui::GetWindowWidth() / 2.f) - (txtSz.x / 2.4f)); ImGui::Text(text);
					ImGui::PopFont();
					if (offset > 0)
						offset -= (ImGui::GetWindowWidth() + 5) * ((1500.0f / ImGui::GetIO().Framerate ) / 10000);
					if (offset <= 0)
					{
						offset -= (ImGui::GetWindowWidth() + 5) * ((1500.0f / ImGui::GetIO().Framerate) / 10000);
						offset = 0;
						reverse = false;
						*done = true;
						oldTime = -1;
						show_popup = false;
					}
				}
			}
			ImGui::PopStyleColor(1);
			ImGui::EndChild();
		}
		ImGui::End();
	}
}