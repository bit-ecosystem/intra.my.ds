
#!/usr/bin/env bash
# ollama_specs_check.sh
# Compact system spec check for running Ollama models on Oracle Linux

set -euo pipefail

divider() { printf "\n%s\n" "---------------------------------------------"; }
section() { divider; printf "%s\n" "$1"; divider; }

human_bytes() {
  # Convert bytes to human-readable
  num="$1"
  awk -v b="$num" 'BEGIN{
    split("B KB MB GB TB",units)
    i=1; while (b>=1024 && i<5){b/=1024;i++}
    printf "%.2f %s\n", b, units[i]
  }'
}

# 1) OS & Kernel
section "OS & Kernel"
if command -v cat >/dev/null; then
  os_name=$(grep -E '^PRETTY_NAME=' /etc/os-release | cut -d= -f2 | tr -d '"')
  kernel=$(uname -r)
  printf "OS           : %s\n" "${os_name:-Unknown}"
  printf "Kernel       : %s\n" "$kernel"
else
  printf "OS/Kernel    : tools missing\n"
fi

# 2) CPU
section "CPU"
if command -v lscpu >/dev/null; then
  model=$(lscpu | awk -F: '/Model name/ {print $2}' | sed 's/^[ \t]*//')
  sockets=$(lscpu | awk -F: '/Socket\(s\)/ {print $2}' | tr -d ' ')
  cores=$(lscpu | awk -F: '/Core\(s\) per socket/ {print $2}' | tr -d ' ')
  threads=$(lscpu | awk -F: '/Thread\(s\) per core/ {print $2}' | tr -d ' ')
  cpus=$(lscpu | awk -F: '/^CPU\(s\)/ {print $2}' | tr -d ' ')
  flags=$(lscpu | awk -F: '/Flags/ {print $2}')
  avx="No"
  echo "$flags" | grep -Eqi 'avx|avx2' && avx="Yes"
  printf "Model        : %s\n" "${model:-Unknown}"
  printf "Sockets      : %s\n" "${sockets:-Unknown}"
  printf "Cores/socket : %s\n" "${cores:-Unknown}"
  printf "Threads/core : %s\n" "${threads:-Unknown}"
  printf "Logical CPUs : %s\n" "${cpus:-Unknown}"
  printf "AVX/AVX2     : %s\n" "$avx"
else
  printf "lscpu not found. Install with: sudo dnf install util-linux\n"
fi

# 3) Memory (RAM & Swap)
section "Memory"
if command -v free >/dev/null; then
  total_ram=$(free -b | awk '/Mem:/ {print $2}')
  avail_ram=$(free -b | awk '/Mem:/ {print $7}')
  swap_total=$(free -b | awk '/Swap:/ {print $2}')
  printf "Total RAM    : %s\n" "$(human_bytes "$total_ram")"
  printf "Avail RAM    : %s\n" "$(human_bytes "$avail_ram")"
  printf "Swap Total   : %s\n" "$(human_bytes "$swap_total")"
else
  printf "free not found.\n"
fi

# 4) Disk (focus on model directory)
section "Disk"
# Determine Ollama model dir (default: /var/lib/ollama)
ollama_dir_default="/var/lib/ollama"
ollama_dir="${OLLAMA_MODELS_DIR:-$ollama_dir_default}"
if command -v df >/dev/null; then
  printf "Model Dir    : %s\n" "$ollama_dir"
  df -h "$ollama_dir" 2>/dev/null || df -h /
else
  printf "df not found.\n"
fi

# 5) GPU (NVIDIA) & CUDA
section "GPU (NVIDIA) & CUDA"
if command -v nvidia-smi >/dev/null; then
  printf "nvidia-smi output:\n"
  nvidia-smi --query-gpu=name,driver_version,cuda_version,memory.total,memory.used,memory.free --format=csv
  # Summary VRAM
  total_vram_mb=$(nvidia-smi --query-gpu=memory.total --format=csv,noheader,nounits | awk '{sum+=$1} END{print sum}')
  printf "Total VRAM   : %.2f GB\n" "$(awk -v m="$total_vram_mb" 'BEGIN{printf m/1024}')"
else
  printf "NVIDIA GPU   : nvidia-smi not found or no NVIDIA GPU.\n"
  printf "If you have NVIDIA, install drivers & CUDA: sudo dnf install -y nvidia-driver\n"
fi

# 6) GPU (AMD ROCm) — optional fallback
section "GPU (AMD ROCm)"
if command -v rocm-smi >/dev/null; then
  printf "rocm-smi output:\n"
  rocm-smi --showproductname --showdriverversion --showvbios --showmeminfo vram
else
  printf "AMD GPU      : rocm-smi not found or no AMD GPU.\n"
fi

# 7) Ollama Runtime
section "Ollama Runtime"
if command -v ollama >/dev/null; then
  printf "Ollama ver   : %s\n" "$(ollama --version)"
  printf "Ollama svc   : "
  if command -v systemctl >/dev/null && systemctl is-active --quiet ollama; then
    printf "active\n"
  else
    printf "inactive or not managed by systemd\n"
  fi
  # Check model storage path if configured
  printf "OLLAMA_MODELS_DIR : %s\n" "${OLLAMA_MODELS_DIR:-(not set; using default)}"
else
  printf "Ollama         : not installed.\n"
  printf "Install: curl -fsSL https://ollama.com/install.sh | sh\n"
fi

# 8) Network (optional quick check)
section "Network (optional)"
if command -v ss >/dev/null; then
  printf "Listening ports (filtered):\n"
  ss -tulpen | awk 'NR==1 || /:(11434|3000)/' || true
else
  printf "ss not found.\n"
fi

# 9) Quick suitability hints
section "Quick Suitability Guide"
printf "Guidance:\n"
printf " - For Qwen2.5-Coder:3B, CPU-only works but GPU gives snappier inference.\n"
printf " - Aim for >=16 GB RAM (32 GB recommended) and NVMe SSD for faster loads.\n"
printf " - NVIDIA GPU with >=6–12 GB VRAM is plenty for 3B quantized models.\n"
printf " - Ensure Ollama is installed and service is running if you’ll serve locally.\n"

echo
