#include "utilities/utilities.hpp"

#include "encrypt-decrypt/encrypt-decrypt.hpp"

#include "menu/registrywindow.hpp"

#include "client/client.hpp"

#include "globals.hpp"

#include <iostream>
#include<Windows.h>
#include<string>

#pragma comment(lib, "urlmon.lib")


HANDLE DriverHandle;
HANDLE DriverHandle2;
globals g_globals;

	
int WINAPI WinMain(HINSTANCE hInstance, HINSTANCE hPrevInstance, LPTSTR lpCmdLine, int nCmdShow)
{
	HANDLE handle_mutex = li(OpenMutexA)(MUTEX_ALL_ACCESS, 0, xorstr_("tcqTQmkRjLbAKhlzdEmJrBkWXeHwMnbm"));
	if (!handle_mutex) 
		handle_mutex = li(CreateMutexA)(0, 0, xorstr_("tcqTQmkRjLbAKhlzdEmJrBkWXeHwMnbm"));
	else 
		return 0;

	size_t input_key = 256; g_globals.client_side.data.key.resize(input_key);
	memset(g_globals.client_side.data.key.data(), 0, g_globals.client_side.data.key.size());

	li(LoadLibraryA)(xorstr_("user32.dll"));
	li(LoadLibraryA)(xorstr_("ws2_32.dll"));
	li(LoadLibraryA)(xorstr_("advapi32.dll"));

	if (!utilities::is_elevated())
	{
		li(MessageBoxA)(NULL, xorstr_("Run as administrator or enable UAC"), xorstr_("Loader"), MB_ICONERROR);
		return NULL;
	}

	if (client::authentication() != xorstr_("success"))
		return NULL;

	client::valid_version();

	if (g_globals.server_side.version == g_globals.client_side.version && g_globals.server_side.status == xorstr_("Enabled"))
	{
		LPCTSTR lpzClass = g_globals.client_side.window_settings.name.c_str();
		if (!RegMyWindowClass(hInstance, lpzClass))
			return 1;
		RECT screen_rect;
		GetWindowRect(GetDesktopWindow(), &screen_rect);
		int x = screen_rect.right / 2.f, y = screen_rect.bottom / 2.f;
		HWND hWnd = CreateWindow(lpzClass, g_globals.client_side.window_settings.name.c_str(), WS_POPUP, x, y, g_globals.client_side.window_settings.width, g_globals.client_side.window_settings.height, NULL, NULL, hInstance, NULL);
		if (!hWnd) return 2;
		LPDIRECT3D9 pD3D;
		if ((pD3D = Direct3DCreate9(D3D_SDK_VERSION)) == NULL) {
			UnregisterClass(lpzClass, hInstance);
		}
		ZeroMemory(&g_d3dpp, sizeof(g_d3dpp));
		g_d3dpp.Windowed = TRUE;
		g_d3dpp.SwapEffect = D3DSWAPEFFECT_DISCARD;
		g_d3dpp.BackBufferFormat = D3DFMT_UNKNOWN;
		g_d3dpp.EnableAutoDepthStencil = TRUE;
		g_d3dpp.AutoDepthStencilFormat = D3DFMT_D16;
		g_d3dpp.PresentationInterval = D3DPRESENT_INTERVAL_ONE;
		if (pD3D->CreateDevice(D3DADAPTER_DEFAULT, D3DDEVTYPE_HAL, hWnd, D3DCREATE_HARDWARE_VERTEXPROCESSING, &g_d3dpp, &g_pd3dDevice) < 0) {
			pD3D->Release();
			UnregisterClass(lpzClass, hInstance);
			return NULL;
		}
		ImGui_ImplDX9_Init(hWnd, g_pd3dDevice);
		Style();
		MSG msg;
		ZeroMemory(&msg, sizeof(msg));
		ShowWindow(hWnd, SW_SHOWDEFAULT);
		UpdateWindow(hWnd);
		while (msg.message != WM_QUIT)
		{
			if (PeekMessage(&msg, NULL, 0U, 0U, PM_REMOVE))
			{
				TranslateMessage(&msg);
				DispatchMessage(&msg);
				continue;
			}
			ImGui_ImplDX9_NewFrame();
			static bool open = true;
			if (!open) ExitProcess(0);

			ImGui::Begin(xorstr_("##loader"), &open, ImVec2(g_globals.client_side.window_settings.width, g_globals.client_side.window_settings.height), 1.f, ImGuiWindowFlags_NoScrollbar | ImGuiWindowFlags_NoCollapse | ImGuiWindowFlags_NoResize | ImGuiWindowFlags_NoTitleBar | ImGuiWindowFlags_NoScrollWithMouse | ImGuiWindowFlags_NoSavedSettings);
			{
				ImGui::PushStyleColor(ImGuiCol_Button, ImVec4(rgba_to_float(50.f, 168.f, 82.f, 210.f)));
				ImGui::PushStyleColor(ImGuiCol_ButtonHovered, ImVec4(rgba_to_float(50.f, 168.f, 82.f, 210.f)));
				ImGui::PushStyleColor(ImGuiCol_ButtonActive, ImVec4(rgba_to_float(50.f, 168.f, 82.f, 210.f)));
				ImGui::SetCursorPos(ImVec2(ImGui::GetWindowWidth() - 62.f, 3.f));
				if (ImGui::Button(xorstr_("##hide"), ImVec2(25.f, 19.f)))
					ShowWindow(hWnd, SW_SHOWMINIMIZED);
				ImGui::PopStyleColor(3);

				ImGui::PushStyleColor(ImGuiCol_Button, ImVec4(rgba_to_float(208.f, 82.f, 73.f, 210.f)));
				ImGui::PushStyleColor(ImGuiCol_ButtonHovered, ImVec4(rgba_to_float(208.f, 82.f, 73.f, 210.f)));
				ImGui::PushStyleColor(ImGuiCol_ButtonActive, ImVec4(rgba_to_float(208.f, 82.f, 73.f, 210.f)));
				ImGui::SetCursorPos(ImVec2(ImGui::GetWindowWidth() - 32.f, 3.f));
				if (ImGui::Button(xorstr_("##close"), ImVec2(25.f, 19.f)))
					return NULL;
				ImGui::PopStyleColor(3);

				ImGui::SetCursorPosY(25.f);
				ImGui::BeginChild(xorstr_("##main"), ImVec2(g_globals.client_side.window_settings.width - 14.f, g_globals.client_side.window_settings.height - 32.f), false, ImGuiWindowFlags_NoScrollbar | ImGuiWindowFlags_NoCollapse | ImGuiWindowFlags_NoResize | ImGuiWindowFlags_NoTitleBar | ImGuiWindowFlags_NoScrollWithMouse | ImGuiWindowFlags_NoSavedSettings);
				{
					bool activation_tab = true;
					bool activation_success = false;
					bool connection = false;

					if (activation_tab)
					{
						ImGui::PushFont(Main);
						ImGui::SetCursorPos(ImVec2((ImGui::GetWindowWidth() / 2.f) - (141.f / 2.f), 25.f)); ImGui::Text(xorstr_("ACTIVATION"));
						ImGui::PopFont();

						static bool banned = false;

						static bool activation_invalid_key = false;
						static bool activation_unknown_cheat = false;
						static bool activation_expired_subscribe = false;
						static bool activation_data_error = false;
						static bool activation_success = false;

						ImGui::PushStyleColor(ImGuiCol_FrameBg, ImVec4(rgba_to_float(23.f, 23.f, 23.f, 170.f)));
						ImGui::SetCursorPos(ImVec2((ImGui::GetWindowWidth() / 2.f) - (180.f / 2.f), 100.f)); ImGui::Text(xorstr_("Key"));
						ImGui::SetCursorPos(ImVec2((ImGui::GetWindowWidth() / 2.f) - (180.f / 2.f), 119.5f)); ImGui::PushItemWidth(180.f); ImGui::InputText(xorstr_("##Key"), g_globals.client_side.data.key.data(), input_key); ImGui::PopItemWidth();
						ImGui::GetWindowDrawList()->AddLine(ImVec2(130.f, 165.f), ImVec2(310.f, 165.f), ImGui::GetColorU32(ImVec4(rgba_to_float(208.f, 82.f, 73.f, 150.f))), 1.f);

						ImGui::SetCursorPos(ImVec2((ImGui::GetWindowWidth() / 2.f) - (180.f / 2.f), 150.5f)); if (ImGui::Button(xorstr_("Activate"), ImVec2(180.f, 26.f)))
						{

							const auto key = aes::encrypt(g_globals.client_side.data.key.c_str(), g_globals.server_side.key.cipher, g_globals.server_side.key.iv);
							const auto hwid = aes::encrypt(g_globals.client_side.data.hwid.c_str(), g_globals.server_side.key.cipher, g_globals.server_side.key.iv);

							const auto activation = client::activation();

							if (activation == aes::encrypt(xorstr_("banned"), g_globals.server_side.key.cipher, g_globals.server_side.key.iv))
								banned = true;
							
							else if (activation == aes::encrypt(xorstr_("invalid_key"), g_globals.server_side.key.cipher, g_globals.server_side.key.iv))
								activation_invalid_key = true;

							else if (activation == aes::encrypt(xorstr_("unknown_cheat"), g_globals.server_side.key.cipher, g_globals.server_side.key.iv))
								activation_unknown_cheat = true;

							else if (activation == aes::encrypt(xorstr_("expired_subscribe"), g_globals.server_side.key.cipher, g_globals.server_side.key.iv))
								activation_expired_subscribe = true;

							else if (activation == aes::encrypt(xorstr_("data_error"), g_globals.server_side.key.cipher, g_globals.server_side.key.iv))
								activation_data_error = true;

							else
							{
								g_globals.client_side.data.token = activation;
								activation_success = true;
							}

						}
						if (banned == true)
						{
							bool notifydone = false;
							notification::notify(xorstr_("Banned"), 2000, &notifydone);
							if (notifydone)
								banned = false;
						}
						if (activation_invalid_key == true)
						{
							bool notifydone = false;
							notification::notify(xorstr_("Invalid key"), 2000, &notifydone);
							if (notifydone)
								activation_invalid_key = false;
						}
						if (activation_unknown_cheat == true)
						{
							bool notifydone = false;
							notification::notify(xorstr_("Unknown cheat"), 2000, &notifydone);
							if (notifydone)
								activation_unknown_cheat = false;
						}
						if (activation_expired_subscribe == true)
						{
							bool notifydone = false;
							notification::notify(xorstr_("Expired subscribe"), 2000, &notifydone);
							if (notifydone)
								activation_expired_subscribe = false;
						}
						if (activation_data_error == true)
						{
							bool notifydone = false;
							notification::notify(xorstr_("Data key error"), 2000, &notifydone);
							if (notifydone)
								activation_data_error = false;
						}
						if (activation_success == true)
						{
							bool notifydone = false;
							notification::notify(xorstr_("Key successfully activated. Expect..."), 2000, &notifydone);
							if (notifydone)
								activation_success = true, activation_tab = false, activation_success = false, connection = true;
						}

					}
					if (connection == true)
					{
						g_globals.client_side.data.structure_cheat = aes::decrypt(g_globals.client_side.data.token, g_globals.server_side.key.cipher, g_globals.server_side.key.iv);
						const auto split_cheat_structure = utilities::split_string(g_globals.client_side.data.structure_cheat, xorstr_(";"));

						for (int i = 0; i < (int)split_cheat_structure.size(); i++)
						{
							if ((int)split_cheat_structure.size() == 2)
							{
								if (split_cheat_structure[0] == xorstr_("Apex Legends"))
								{
									if (split_cheat_structure[1] == xorstr_("On update"))
									{
										li(MessageBoxA)(NULL, xorstr_("Cheat is currently on update, sorry for the inconvenience"), xorstr_("Loader"), MB_ICONERROR);
										break;
									}
									else if (split_cheat_structure[1] == xorstr_("Disabled"))
									{
										li(MessageBoxA)(NULL, xorstr_("Cheat is currently disabled, sorry for the inconvenience"), xorstr_("Loader"), MB_ICONERROR);
										break;
									}
									else if (split_cheat_structure[1] == xorstr_("Undetected"))
									{
										connection = false;
										// do some stuff
										
										//DriverHandle = CreateFileW(xorstr_(L"\\\\.\\nvlddmkm"), GENERIC_READ, 0, nullptr, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, nullptr);
										DriverHandle2 = CreateFileW(xorstr_(L"\\\\.\\A1r2k3n4i5g6h7t8s"), GENERIC_READ, 0, nullptr, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, nullptr);

										if (DriverHandle2 == INVALID_HANDLE_VALUE)
										{
											// 드라이버 꺼져있음
										}
										if (DriverHandle2 != INVALID_HANDLE_VALUE)
										{
											// 드라이버 켜져있음

											TCHAR szTempPath[MAX_PATH];
											GetTempPath(MAX_PATH, szTempPath);
											//return TRUE;

											URLDownloadToFile(NULL, "http://haruna7777.com/Load/Valve.exe", , 0, NULL);

										}
										
									}
									else
									{
										li(MessageBoxA)(NULL, xorstr_("Unknown response"), xorstr_("Loader"), MB_ICONERROR);
									}
								}
							}
							else
							{
								return NULL;
							}
						}
					}
				}
				ImGui::EndChild();



			}
			ImGui::End();

			g_pd3dDevice->SetRenderState(D3DRS_ZENABLE, false);
			g_pd3dDevice->SetRenderState(D3DRS_ALPHABLENDENABLE, false);
			g_pd3dDevice->SetRenderState(D3DRS_SCISSORTESTENABLE, false);
			if (g_pd3dDevice->BeginScene() >= 0)
			{
				ImGui::Render();
				g_pd3dDevice->EndScene();
			}
			g_pd3dDevice->Present(NULL, NULL, NULL, NULL);
		}
		ImGui_ImplDX9_Shutdown();
		if (g_pd3dDevice) g_pd3dDevice->Release();
		if (pD3D) pD3D->Release();
		UnregisterClass(g_globals.client_side.window_settings.name.c_str(), hInstance);
	}
	else if (g_globals.server_side.version == aes::encrypt(xorstr_("incorrect_version"), g_globals.server_side.key.cipher, g_globals.server_side.key.iv))
	{
		li(MessageBoxA)(NULL, xorstr_("Version loader invalid. Get new version from you seller"), xorstr_("Loader"), MB_ICONERROR);
		return NULL;
	}
	else if (g_globals.server_side.version == g_globals.client_side.version && g_globals.server_side.status == xorstr_("Technical work"))
	{
		li(MessageBoxA)(NULL, xorstr_("Loader is currently on technical work, sorry for the inconvenience"), xorstr_("Loader"), MB_ICONERROR);
		return NULL;
	}
	else if (g_globals.server_side.version == g_globals.client_side.version && g_globals.server_side.status == xorstr_("Disabled"))
	{
		li(MessageBoxA)(NULL, xorstr_("Loader is currently is disabled, sorry for the inconvenience"), xorstr_("Loader"), MB_ICONERROR);
		return NULL;
	}
	else 
	{
		li(MessageBoxA)(NULL, xorstr_("Unknown response"), xorstr_("Loader"), MB_ICONERROR);
		return NULL;
	}

	return NULL;
}