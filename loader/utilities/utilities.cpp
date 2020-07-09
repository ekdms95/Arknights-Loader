#include "utilities.hpp"
#include "../encrypt-decrypt/md5.hpp"

namespace utilities
{
	 std::string get_random_string(size_t length)
	 {
		const static std::string chrs = xorstr_("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

		thread_local static std::mt19937 rg{ std::random_device{}() };
		thread_local static std::uniform_int_distribution<std::string::size_type> pick(0, sizeof(chrs) - 2);

		std::string s;

		s.reserve(length);

		while (length--)
			s += chrs[pick(rg)];

		return s;
	 }
	 void strip_string(std::string& str)
	 {
		str.erase(std::remove_if(str.begin(), str.end(), [](int c) {return !(c > 32 && c < 127); }), str.end());
	 }
	 std::vector<std::string> split_string(const std::string& str, const std::string& delim)
	 {
		std::vector<std::string> tokens;
		size_t prev = 0, pos = 0;

		do
		{
			pos = str.find(delim, prev);
			if (pos == std::string::npos) pos = str.length();
			std::string token = str.substr(prev, pos - prev);
			if (!token.empty()) tokens.push_back(token);
			prev = pos + delim.length();

		} while (pos < str.length() && prev < str.length());

		return tokens;
	 }
	 std::string request_to_server(std::string site, std::string param)
	 {
		HINTERNET hInternet = InternetOpenW(xorstr_(L"User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36"), INTERNET_OPEN_TYPE_DIRECT, NULL, NULL, 0);

		if (hInternet == NULL)
		{
			li(MessageBoxA)(NULL, xorstr_("Error sending message to server"), xorstr_("Loader"), MB_ICONERROR);
			return NULL;
		}
		else
		{
			std::wstring widestr;
			for (int i = 0; i < site.length(); ++i)
			{
				widestr += wchar_t(site[i]);
			}
			const wchar_t* site_name = widestr.c_str();

			std::wstring widestr2;
			for (int i = 0; i < param.length(); ++i)
			{
				widestr2 += wchar_t(param[i]);
			}
			const wchar_t* site_param = widestr2.c_str();

			HINTERNET hConnect = li(InternetConnectW)(hInternet, site_name, 80, NULL, NULL, INTERNET_SERVICE_HTTP, 0, NULL);

			if (hConnect == NULL)
			{
				li(MessageBoxA)(NULL, xorstr_("Error sending message to server"), xorstr_("Loader"), MB_ICONERROR);
				return NULL;
			}
			else
			{
				const wchar_t* parrAcceptTypes[] = { xorstr_(L"text/*"), NULL };

				HINTERNET hRequest = li(HttpOpenRequestW)(hConnect, xorstr_(L"POST"), site_param, NULL, NULL, parrAcceptTypes, 0, 0);

				if (hRequest == NULL)
				{
					li(MessageBoxA)(NULL, xorstr_("Error sending message to server"), xorstr_("Loader"), MB_ICONERROR);
					return NULL;
				}
				else
				{
					BOOL bRequestSent = li(HttpSendRequestW)(hRequest, NULL, 0, NULL, 0);

					if (!bRequestSent)
					{
						li(MessageBoxA)(NULL, xorstr_("Error sending message to server"), xorstr_("Loader"), MB_ICONERROR);
						return NULL;
					}
					else
					{
						std::string strResponse;
						const int nBuffSize = 1024;
						char buff[nBuffSize];

						BOOL bKeepReading = true;
						DWORD dwBytesRead = -1;

						while (bKeepReading && dwBytesRead != 0)
						{
							bKeepReading = li(InternetReadFile)(hRequest, buff, nBuffSize, &dwBytesRead);
							strResponse.append(buff, dwBytesRead);
						}
						return strResponse;
					}
					li(InternetCloseHandle)(hRequest);
				}
				li(InternetCloseHandle)(hConnect);
			}
			li(InternetCloseHandle)(hInternet);
		}
	 }
	 std::string get_hwid()
	 {
		std::string result = _xor_("");

		HANDLE hDevice = li(CreateFileA)(_xor_("\\\\.\\PhysicalDrive0").c_str(), (DWORD)nullptr, FILE_SHARE_READ | FILE_SHARE_WRITE, (LPSECURITY_ATTRIBUTES)nullptr, OPEN_EXISTING, (DWORD)nullptr, (HANDLE)nullptr);

		if (hDevice == INVALID_HANDLE_VALUE) return result;

		STORAGE_PROPERTY_QUERY storagePropertyQuery;
		ZeroMemory(&storagePropertyQuery, sizeof(STORAGE_PROPERTY_QUERY));
		storagePropertyQuery.PropertyId = StorageDeviceProperty;
		storagePropertyQuery.QueryType = PropertyStandardQuery;

		STORAGE_DESCRIPTOR_HEADER storageDescriptorHeader = { 0 };
		DWORD dwBytesReturned = 0;

		li(DeviceIoControl)
			(
				hDevice,
				IOCTL_STORAGE_QUERY_PROPERTY,
				&storagePropertyQuery,
				sizeof(STORAGE_PROPERTY_QUERY),
				&storageDescriptorHeader,
				sizeof(STORAGE_DESCRIPTOR_HEADER),
				&dwBytesReturned,
				nullptr
				);

		const DWORD dwOutBufferSize = storageDescriptorHeader.Size;
		BYTE* pOutBuffer = new BYTE[dwOutBufferSize];
		ZeroMemory(pOutBuffer, dwOutBufferSize);

		li(DeviceIoControl)
			(
				hDevice,
				IOCTL_STORAGE_QUERY_PROPERTY,
				&storagePropertyQuery,
				sizeof(STORAGE_PROPERTY_QUERY),
				pOutBuffer,
				dwOutBufferSize,
				&dwBytesReturned,
				nullptr
				);

		STORAGE_DEVICE_DESCRIPTOR* pDeviceDescriptor = (STORAGE_DEVICE_DESCRIPTOR*)pOutBuffer;

		if (pDeviceDescriptor->SerialNumberOffset)
		{
			result += std::string((char*)(pOutBuffer + pDeviceDescriptor->SerialNumberOffset));
		}

		if (pDeviceDescriptor->ProductRevisionOffset)
		{
			result += std::string((char*)(pOutBuffer + pDeviceDescriptor->ProductRevisionOffset));
		}

		if (pDeviceDescriptor->ProductIdOffset)
		{
			result += std::string((char*)(pOutBuffer + pDeviceDescriptor->ProductIdOffset));
		}

		uint32_t regs[4];
		__cpuid((int*)regs, 0);

		std::string vendor;

		vendor += std::string((char*)&regs[1], 4);
		vendor += std::string((char*)&regs[3], 4);
		vendor += std::string((char*)&regs[2], 4);

		result += std::string(vendor);

		strip_string(result);

		delete[] pOutBuffer;
		li(CloseHandle)(hDevice);

		result = md5::create_from_string(md5::create_from_string(result));

		return result;
	 }
	 bool write_file(const char* path, const char* buffer, size_t size)
	 {
		std::ofstream file_ofstream(path, std::ios_base::out | std::ios_base::binary);

		if (!file_ofstream.write(buffer, size))
			return false;

		file_ofstream.close();
		return true;
	 }
	 bool read_file(const std::string& file_path, std::vector<uint8_t>* out_buffer)
	 {
		std::ifstream file_ifstream(file_path, std::ios::binary);

		if (!file_ifstream)
			return false;

		out_buffer->assign((std::istreambuf_iterator<char>(file_ifstream)), std::istreambuf_iterator<char>());
		file_ifstream.close();

		return true;
	 }
	 bool is_elevated()
	 {
		 bool result = false;
		 HANDLE token = nullptr;

		 if (get_export<decltype(&OpenProcessToken)>(_xor_("advapi32.dll"), _xor_("OpenProcessToken"))(li(GetCurrentProcess)(), TOKEN_QUERY, &token))
		 {
			 TOKEN_ELEVATION elevation;
			 DWORD size = sizeof(TOKEN_ELEVATION);

			 if (get_export<decltype(&GetTokenInformation)>(_xor_("advapi32.dll"), _xor_("GetTokenInformation"))(token, TokenElevation, &elevation, sizeof(elevation), &size))
			 {
				 result = elevation.TokenIsElevated;
			 }
		 }

		 if (token)li(CloseHandle)(token);

		 return result;
	 }
}