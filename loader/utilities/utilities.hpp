#pragma once
#include <windows.h>
#include <string>
#include <random>
#include <vector>
#include <WinInet.h>
#include <fstream>

#include "lazy_importer.hpp"

#pragma comment(lib, "WinINet.lib")


namespace utilities
{
	extern  std::string get_random_string(size_t length);
	extern  void strip_string(std::string& str);
	extern  std::vector<std::string> split_string(const std::string& str, const std::string& delim);
	extern  std::string request_to_server(std::string site, std::string param);
	extern  std::string get_hwid();
	extern  bool write_file(const char* path, const char* buffer, size_t size);
	extern  bool read_file(const std::string& file_path, std::vector<uint8_t>* out_buffer);
	extern  bool is_elevated();
	template<class T>
	 T get_export(const std::string& dll_name, const std::string& function_name)
	{
		return (T)(li(GetProcAddress)(li(GetModuleHandleA)(dll_name.c_str()), function_name.c_str()));
	}
}