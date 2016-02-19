require './matrix'
require './string_scanner'
require './dict'

lengths = [ 5, 6 ]
matrix = Matrix.new [
  %w[ p e a c ],
  %w[ o e m h ],
  %w[ r g e n ],
  %w[ a n l o ],
]
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
pp results.sort

