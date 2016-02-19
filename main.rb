require './matrix'
require './string_scanner'
require './dict'

def usage
  puts "#{$0} <length[,length...]> <row1,row2...>"
  exit 1
end
usage if ARGV.length != 2

lengths = ARGV[0].split(/,/).map(&:to_i)
matrix = Matrix.new ARGV[1].split(/,/).map{|row|row.split(//)}
scanner = StringScanner.new matrix
results = Set.new
(0...matrix.x_max).each do |x|
  (0...matrix.y_max).each do |y|
    lengths.each do |length|
      dict = Dict.new(length)
      hits = scanner.scan(length, x, y)
      results.merge hits & dict.dict
    end
  end
end
puts results.sort.to_a

