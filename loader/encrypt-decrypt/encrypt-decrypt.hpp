#pragma once
#include <windows.h>
#include <cryptopp/aes.h>
#include <cryptopp/modes.h>
#include <cryptopp/base64.h>

#pragma comment(lib, "cryptlib.lib")

namespace aes 
{
	extern std::string encrypt(const std::string& str, const std::string& cipher_key, const std::string& iv_key);
	extern std::string decrypt(const std::string& str, const std::string& cipher_key, const std::string& iv_key);
}

namespace base_64
{
	extern std::string encrypt(unsigned char const* bytes_to_encode, size_t in_len);
	extern std::string decrypt(std::string const& str);
	inline bool is_base64(unsigned char c)
	{
		return (isalnum(c) || (c == '+') || (c == '/'));
	}
}

