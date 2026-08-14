# Palet Warna — Perpustakaan Perta Arun Gas (PAG)

Tema "Pertamina Energy". Semua warna didefinisikan sebagai CSS custom property
`oklch` di `src/styles.css`, lalu dipetakan ke utility Tailwind lewat blok `@theme inline`.
Gunakan token semantik (`bg-primary`, `text-muted-foreground`) — jangan hardcode `#hex` di komponen.

## Warna Brand PAG

| Token | HEX | oklch | Penggunaan |
| --- | --- | --- | --- |
| `--pag-red` | `#C41E3A` | `oklch(0.53083 0.19688 19.54)` | Warna utama, tombol, logo |
| `--pag-coral` | `#E85D3A` | `oklch(0.65215 0.17995 35.224)` | Aksen gradasi, primary mode gelap |
| `--pag-orange` | `#F7931E` | `oklch(0.7525 0.16534 62.217)` | Secondary, highlight |
| `--pag-yellow` | `#FFC107` | `oklch(0.84417 0.17216 84.934)` | Accent, indikator aktif |
| `--pag-ink` | `#1A1A1A` | `oklch(0.21779 0 none)` | Teks utama, background gelap |
| `--pag-muted` | `#4A4A4A` | `oklch(0.40912 0 none)` | Teks sekunder |
| `--pag-cream` | `#FFFAF5` | `oklch(0.98757 0.00852 67.727)` | Background hangat |

Gradasi brand:

```css
--pag-gradient: linear-gradient(135deg, var(--pag-red) 0%, var(--pag-coral) 50%, var(--pag-orange) 100%);
```

## Token Semantik — Mode Terang

| Token | HEX | oklch |
| --- | --- | --- |
| `--background` | `#FFFAF5` | `oklch(0.98757 0.00852 67.727)` |
| `--foreground` | `#1A1A1A` | `oklch(0.21779 0 none)` |
| `--card` / `--popover` | `#FFFFFF` | `oklch(1 0 none)` |
| `--primary` | `#C41E3A` | `oklch(0.53083 0.19688 19.54)` |
| `--primary-foreground` | `#FFFFFF` | `oklch(1 0 none)` |
| `--secondary` | `#F7931E` | `oklch(0.7525 0.16534 62.217)` |
| `--accent` | `#FFC107` | `oklch(0.84417 0.17216 84.934)` |
| `--muted` | `#F5F5F5` | `oklch(0.97015 0 none)` |
| `--muted-foreground` | `#4A4A4A` | `oklch(0.40912 0 none)` |
| `--border` | `#E2DDD7` | `oklch(0.9 0.01 70)` |
| `--input` | `#F2EEE9` | `oklch(0.95 0.008 70)` |
| `--ring` | `#C41E3A` | `oklch(0.53083 0.19688 19.54)` |
| `--destructive` | `#E40016` | `oklch(0.577 0.245 27.325)` |
| `--sidebar` | `#FBF8F5` | `oklch(0.98 0.005 70)` |

## Token Semantik — Mode Gelap (`.dark`)

| Token | HEX | oklch |
| --- | --- | --- |
| `--background` | `#1A1A1A` | `oklch(0.21779 0 none)` |
| `--foreground` | `#FFFAF5` | `oklch(0.98757 0.00852 67.727)` |
| `--card` / `--popover` / `--muted` | `#2C2823` | `oklch(0.279 0.01 70)` |
| `--primary` | `#E85D3A` | `oklch(0.65215 0.17995 35.224)` |
| `--secondary` | `#F7931E` | `oklch(0.7525 0.16534 62.217)` |
| `--accent` / `--muted-foreground` | `#FFC107` | `oklch(0.84417 0.17216 84.934)` |
| `--border` | `rgba(255,255,255,0.10)` | `oklch(1 0 none / 10%)` |
| `--input` | `rgba(255,255,255,0.15)` | `oklch(1 0 none / 15%)` |
| `--destructive` | `#FF6568` | `oklch(0.704 0.191 22.216)` |

## Warna Chart

| Token | Terang | Gelap |
| --- | --- | --- |
| `--chart-1` | `#C41E3A` | `#E85D3A` |
| `--chart-2` | `#E85D3A` | `#F7931E` |
| `--chart-3` | `#F7931E` | `#FFC107` |
| `--chart-4` | `#FFC107` | `#C41E3A` |
| `--chart-5` | `#1A1A1A` | `#FFFAF5` |

## Tipografi

| Variabel | Font | Penggunaan |
| --- | --- | --- |
| `--font-heading` | Urbanist (300–800) | Judul `h1`–`h6` |
| `--font-body` | Epilogue (300–700) | Teks isi |

## Radius

`--radius: 0.75rem` → `rounded-sm` (4px kurang) hingga `rounded-4xl` (`radius + 16px`).

## Contoh Pemakaian

```tsx
// Token semantik (disarankan)
<button className="rounded-full bg-primary text-primary-foreground hover:bg-primary/90" />

// Token brand khusus (gradasi/aksen)
<div className="bg-gradient-to-r from-[var(--pag-red)] to-[var(--pag-orange)]" />
```